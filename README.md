# PhotoCraft Studio — Flask port

This workspace contains a Flask port of the original PHP app. It supports two background-removal options:

- `rembg` — local server-side removal using the `rembg` Python library (requires model download and heavier dependencies)
- `removebg` — proxy to the remove.bg API (uses `REMOVE_BG_API_KEY` environment variable if set; otherwise falls back to the provided default key)

Usage

1. Create a Python environment and install dependencies:

```bash
python -m venv venv
venv\Scripts\activate
pip install -r requirements.txt
```

2. (Optional) Set the remove.bg API key if you want to use that service:

```powershell
setx REMOVE_BG_API_KEY "your_api_key_here"
```

3. Run the app:

```bash
python app.py
```

Open http://localhost:5000 and use the `Settings` select in the Background step to switch between `rembg` and `removebg`. The choice is remembered in your browser's `localStorage`.

Notes
- If using `rembg`, the server may need to download model files and install additional dependencies; consult the rembg docs if you run into installation issues.
- The `removebg` API will forward the provider's response; ensure your API key has credits.
