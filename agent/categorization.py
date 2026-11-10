import re

FEATURE_MAP = {
    "Authentication": ["login", "logout", "password", "auth", "authentication", "authorization", "session", "token", "signin", "signup", "credential", "register", "oauth", "jwt", "pass"],
    "Payments": ["payment", "invoice", "billing", "discount", "transaction", "checkout", "stripe", "cart", "pay", "order"],
    "Database": ["sql", "database", "query", "migration", "postgres", "mysql", "sqlite", "model", "schema", "table"],
    "API": ["api", "http", "request", "response", "endpoint", "json", "rest", "route", "controller", "fetch", "axios"],
    "Frontend": ["button", "form", "css", "react", "vue", "html", "render", "ui", "view", "component", "screen", "modal", "layout"],
    "Performance": ["slow", "timeout", "memory", "performance", "latency", "lag", "freeze"],
}

DANGER_WORDS = [
    "crash",
    "fatal",
    "blocker",
    "broken",
    "error",
    "exception",
    "undeclared",
    "mismatch",
]


def detect_feature(text: str) -> str:
    tokens = set(re.findall(r"[a-z0-9]+", text.lower()))
    for name, keywords in FEATURE_MAP.items():
        if any(keyword in tokens for keyword in keywords):
            return name
    return "General"


def calculate_priority(text: str) -> str:
    combined_text = text.lower()
    for word in DANGER_WORDS:
        if word in combined_text:
            return "High"
    return "Medium"
