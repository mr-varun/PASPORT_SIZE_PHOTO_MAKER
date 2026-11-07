from flask import Flask, render_template, request, send_file, jsonify
import os
import io
import requests

app = Flask(__name__, static_folder='static', template_folder='templates')

# Try to import rembg; if missing, we'll return an error when used
try:
    from rembg import remove as rembg_remove
except Exception:
    rembg_remove = None

# Settings file to persist chosen service on server
SETTINGS_FILE = os.path.join(app.root_path, 'bg_service.txt')

def read_service_setting():
    try:
        if os.path.exists(SETTINGS_FILE):
            with open(SETTINGS_FILE, 'r', encoding='utf-8') as f:
                val = f.read().strip()
                if val in ('rembg', 'removebg'):
                    return val
    except Exception:
        pass
    return 'rembg'

def write_service_setting(val):
    try:
        with open(SETTINGS_FILE, 'w', encoding='utf-8') as f:
            f.write(val)
        return True
    except Exception:
        return False


@app.route('/')
def index():
    return render_template('index.html')


@app.route('/removebg', methods=['POST'])
def removebg():
    # Expect 'image' file and optional 'service' form field: 'rembg' or 'removebg'
    if 'image' not in request.files:
        return jsonify({'error': 'No image uploaded'}), 400

    f = request.files['image']
    service = request.form.get('service') or request.args.get('service') or read_service_setting() or os.getenv('BG_SERVICE', 'rembg')

    img_bytes = f.read()

    if service == 'removebg':
        api_key = os.getenv('REMOVE_BG_API_KEY')
        if not api_key:
            return jsonify({'error': 'Remove.bg API key not configured (REMOVE_BG_API_KEY)'}), 500

        resp = requests.post(
            'https://api.remove.bg/v1.0/removebg',
            headers={'X-Api-Key': api_key},
            files={'image_file': (f.filename, img_bytes)},
            data={'size': 'auto'},
            timeout=120,
        )

        if resp.status_code != 200:
            try:
                return (resp.content, resp.status_code, {'Content-Type': 'application/json'})
            except Exception:
                return jsonify({'error': 'remove.bg failed', 'status': resp.status_code}), 502

        # Forward image bytes and content-type
        content_type = resp.headers.get('Content-Type', 'image/png')
        return (resp.content, 200, {'Content-Type': content_type})

    elif service == 'rembg':
        if rembg_remove is None:
            return jsonify({'error': 'rembg library not available. Install rembg.'}), 500
        try:
            out_bytes = rembg_remove(img_bytes)
            return send_file(io.BytesIO(out_bytes), mimetype='image/png')
        except Exception as e:
            return jsonify({'error': 'rembg processing failed', 'details': str(e)}), 500

    else:
        return jsonify({'error': 'Unknown service'}), 400


@app.route('/settings', methods=['GET', 'POST'])
def settings():
    if request.method == 'GET':
        return jsonify({'service': read_service_setting()})
    # POST - accept JSON or form
    svc = None
    if request.is_json:
        data = request.get_json(silent=True) or {}
        svc = data.get('service')
    else:
        svc = request.form.get('service')
    if svc not in ('rembg', 'removebg'):
        return jsonify({'error': 'Invalid service'}), 400
    ok = write_service_setting(svc)
    if not ok:
        return jsonify({'error': 'Failed to write settings'}), 500
    return jsonify({'service': svc})


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
