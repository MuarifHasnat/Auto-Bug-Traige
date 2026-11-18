"""
Production-grade persistent microservice for AutoBugTriage AI Agent.
Supports high-throughput REST API requests (FastAPI with OpenAPI / Swagger UI,
with built-in standard library http.server fallback).

Usage:
  python agent/server.py --port 8001
"""

import sys
import json
import logging
import importlib
from pathlib import Path
from typing import List, Optional, Dict, Any

# Ensure agent directory is in sys.path
AGENT_DIR = Path(__file__).resolve().parent
if str(AGENT_DIR) not in sys.path:
    sys.path.insert(0, str(AGENT_DIR))

from triage import analyze_report

logging.basicConfig(level=logging.INFO, format="%(asctime)s [%(levelname)s] %(message)s")
logger = logging.getLogger("AutoBugTriage.Server")

# Type annotations and fallback bindings for static analysis
BaseModel: Any = None
Field: Any = None
TriageRequestModel: Any = None

# Check if Pydantic is available dynamically
try:
    pydantic_mod = importlib.import_module("pydantic")
    BaseModel = getattr(pydantic_mod, "BaseModel")
    Field = getattr(pydantic_mod, "Field")

    class TriageRequestModel(BaseModel):
        title: str = Field(
            ...,
            description="Issue title or anomaly summary",
            example="ZeroDivisionError in invoice calculation logic"
        )
        description: Optional[str] = Field(
            "",
            description="Detailed bug report description or user observation",
            example="When processing empty checkout cart, payment calculations crash with ZeroDivisionError."
        )
        reproduction_steps: Optional[List[str]] = Field(
            default_factory=list,
            description="Step-by-step reproduction sequence"
        )
        expected_result: Optional[str] = Field(
            "",
            description="Expected correct software behavior"
        )
        github_repo: Optional[str] = Field(
            None,
            description="GitHub repository URL to scan for culprit files",
            example="https://github.com/MuarifHasnat/Auto-Bug-Traige"
        )
        labels: Optional[str] = Field(
            "",
            description="Comma-separated tags or labels"
        )
        code_snippet: Optional[str] = Field(
            "",
            description="Optional source code snippet or stack trace"
        )
        language: Optional[str] = Field(
            "auto",
            description="Target programming language (e.g. Kotlin, Python, PHP, JavaScript)"
        )
        screenshot_path: Optional[str] = Field(
            None,
            description="Path to error screenshot image on disk"
        )
        existing_bugs: Optional[List[Dict[str, Any]]] = Field(
            default_factory=list,
            description="List of existing bug records for vector similarity comparison"
        )
        developers: Optional[List[Dict[str, Any]]] = Field(
            default_factory=list,
            description="List of team developers with skill tags and current task counts"
        )

    PYDANTIC_AVAILABLE = True
except ImportError:
    PYDANTIC_AVAILABLE = False


# Fallback bindings for FastAPI / Uvicorn symbols
FastAPI: Any = None
HTTPException: Any = None
Request: Any = None
Body: Any = None
JSONResponse: Any = None
uvicorn: Any = None
app: Any = None

# Check if FastAPI / Uvicorn is available dynamically
try:
    fastapi_mod = importlib.import_module("fastapi")
    FastAPI = getattr(fastapi_mod, "FastAPI")
    HTTPException = getattr(fastapi_mod, "HTTPException")
    Request = getattr(fastapi_mod, "Request")
    Body = getattr(fastapi_mod, "Body")

    responses_mod = importlib.import_module("fastapi.responses")
    JSONResponse = getattr(responses_mod, "JSONResponse")

    uvicorn = importlib.import_module("uvicorn")
    FASTAPI_AVAILABLE = True
except Exception:
    FASTAPI_AVAILABLE = False


if FASTAPI_AVAILABLE and FastAPI is not None:
    app = FastAPI(
        title="AutoBugTriage Autonomous AI Microservice",
        description=(
            "Enterprise-grade distributed microservice for Autonomous Bug Triaging, "
            "AST Code Diagnostics, GitHub Culprit File Localization, and Multi-Factor "
            "Developer Dispatching."
        ),
        version="2.5.0",
        docs_url="/docs",
        redoc_url="/redoc"
    )

    @app.get("/health", summary="Microservice Health Probe")
    async def health_check():
        return {
            "status": "healthy",
            "service": "AutoBugTriage AI Microservice",
            "version": "2.5.0",
            "engine": "FastAPI + Uvicorn"
        }

    if PYDANTIC_AVAILABLE and TriageRequestModel is not None:
        @app.post(
            "/api/v1/triage",
            summary="Execute Autonomous Bug Triage & Source Code Localization",
            response_description="Complete Explainable AI triage output with diagnostics and developer routing."
        )
        async def run_triage(payload: TriageRequestModel):
            try:
                data = payload.model_dump()
                logger.info(f"Received FastAPI triage request for: {data.get('title', 'Unknown')}")
                result = analyze_report(data)
                return JSONResponse(content=result)
            except Exception as e:
                logger.error(f"Triage microservice error: {e}", exc_info=True)
                raise HTTPException(status_code=500, detail=str(e))
    else:
        @app.post("/api/v1/triage", summary="Execute Autonomous Bug Triage")
        async def run_triage(request: Request):
            try:
                payload = await request.json()
                logger.info(f"Received triage request for: {payload.get('title', 'Unknown')}")
                result = analyze_report(payload)
                return JSONResponse(content=result)
            except Exception as e:
                logger.error(f"Triage microservice error: {e}", exc_info=True)
                raise HTTPException(status_code=500, detail=str(e))

    def start_server(port: int = 8001):
        logger.info(f"Starting FastAPI Microservice at http://127.0.0.1:{port} (Interactive Swagger UI: http://127.0.0.1:{port}/docs)")
        uvicorn.run(app, host="127.0.0.1", port=port, log_level="info")

else:
    # High-performance built-in HTTP server fallback (Zero Dependencies)
    from http.server import HTTPServer, BaseHTTPRequestHandler

    class TriageHTTPHandler(BaseHTTPRequestHandler):
        def _set_headers(self, status=200):
            self.send_response(status)
            self.send_header("Content-Type", "application/json")
            self.end_headers()

        def do_GET(self):
            if self.path == "/health":
                self._set_headers(200)
                self.wfile.write(json.dumps({"status": "healthy", "engine": "builtin-http"}).encode())
            else:
                self._set_headers(404)
                self.wfile.write(json.dumps({"error": "Not Found"}).encode())

        def do_POST(self):
            if self.path in ["/api/v1/triage", "/triage"]:
                try:
                    content_length = int(self.headers.get("Content-Length", 0))
                    body = self.rfile.read(content_length).decode("utf-8")
                    payload = json.loads(body) if body else {}
                    logger.info(f"HTTP Server processing triage for: {payload.get('title', 'Report')}")
                    result = analyze_report(payload)
                    self._set_headers(200)
                    self.wfile.write(json.dumps(result).encode("utf-8"))
                except Exception as exc:
                    logger.error(f"HTTP handler error: {exc}", exc_info=True)
                    self._set_headers(500)
                    self.wfile.write(json.dumps({"error": str(exc)}).encode("utf-8"))
            else:
                self._set_headers(404)
                self.wfile.write(json.dumps({"error": "Unknown Endpoint"}).encode())

        def log_message(self, format, *args):
            logger.info("%s - - [%s] %s" % (self.client_address[0], self.log_date_time_string(), format % args))

    def start_server(port: int = 8001):
        server_address = ("127.0.0.1", port)
        httpd = HTTPServer(server_address, TriageHTTPHandler)
        logger.info(f"AutoBugTriage persistent server listening at http://127.0.0.1:{port}/api/v1/triage")
        try:
            httpd.serve_forever()
        except KeyboardInterrupt:
            logger.info("Stopping microservice...")
            httpd.server_close()


if __name__ == "__main__":
    port = 8001
    if "--port" in sys.argv:
        try:
            port = int(sys.argv[sys.argv.index("--port") + 1])
        except (IndexError, ValueError):
            pass
    start_server(port)
