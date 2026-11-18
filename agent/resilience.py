"""
Network resilience utilities for external API and HTTP requests.
Implements exponential backoff and jitter without requiring external packages.
"""

import time
import functools
import logging
from typing import Callable, Any, Optional

logger = logging.getLogger("AutoBugTriage.Resilience")


def exponential_backoff(
    max_retries: int = 3,
    base_delay: float = 1.5,
    max_delay: float = 10.0,
    retry_exceptions: tuple = (Exception,),
    should_retry: Optional[Callable[[Exception], bool]] = None
):
    """
    Decorator that retries a function call using exponential backoff.
    Waits base_delay * (2 ** attempt) seconds between failed attempts.
    """
    def decorator(func: Callable) -> Callable:
        @functools.wraps(func)
        def wrapper(*args, **kwargs) -> Any:
            attempt = 0
            while True:
                try:
                    return func(*args, **kwargs)
                except retry_exceptions as exc:
                    if should_retry and not should_retry(exc):
                        raise
                    attempt += 1
                    if attempt > max_retries:
                        logger.warning(f"{func.__name__} failed after {max_retries} retries: {exc}")
                        raise
                    delay = min(base_delay * (2 ** (attempt - 1)), max_delay)
                    logger.info(f"{func.__name__} attempt {attempt} failed ({exc}). Retrying in {delay:.1f}s...")
                    time.sleep(delay)
        return wrapper
    return decorator

