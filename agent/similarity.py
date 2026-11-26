import math
import re
from collections import Counter
from typing import Any

# Try importing scikit-learn for production TF-IDF semantic vectorization
try:
    from sklearn.feature_extraction.text import TfidfVectorizer
    from sklearn.metrics.pairwise import cosine_similarity as sk_cosine_similarity
    SKLEARN_AVAILABLE = True
except ImportError:
    SKLEARN_AVAILABLE = False


def _fallback_vectorize(value):
    tokens = re.findall(r"[a-z0-9+#_]+", value.lower())
    counts = Counter(tokens)
    total = sum(counts.values()) or 1
    return {token: count / total for token, count in counts.items()}


def cosine_similarity(text, candidates):
    if SKLEARN_AVAILABLE and candidates:
        try:
            corpus = [text] + list(candidates)
            vectorizer = TfidfVectorizer(token_pattern=r"[a-zA-Z0-9+#_]+", stop_words="english")
            tfidf_matrix: Any = vectorizer.fit_transform(corpus)
            scores = sk_cosine_similarity(tfidf_matrix[0:1], tfidf_matrix[1:]).flatten()
            return [float(s) for s in scores]
        except Exception:
            pass  # Fall through to resilient native TF cosine similarity

    source = _fallback_vectorize(text)
    source_norm = math.sqrt(sum(value * value for value in source.values()))
    scores = []
    for candidate in candidates:
        target = _fallback_vectorize(candidate)
        target_norm = math.sqrt(sum(value * value for value in target.values()))
        dot = sum(source.get(token, 0) * value for token, value in target.items())
        scores.append(dot / (source_norm * target_norm) if source_norm and target_norm else 0.0)
    return scores


def detect_duplicates(title, description, past_bugs, threshold=0.75):
    is_duplicate = False
    duplicate_of = None
    similarity_percent = 0.0
    top_matches = []

    if past_bugs and len(past_bugs) > 0:
        all_texts = [b.get("title", "") + " " + b.get("description", "") for b in past_bugs]
        current_text = title + " " + description

        scores = cosine_similarity(current_text, all_texts)
        if scores:
            ranked = sorted(
                zip(scores, past_bugs),
                key=lambda item: item[0],
                reverse=True,
            )
            top_matches = [
                {
                    "id": bug.get("id"),
                    "title": bug.get("title", ""),
                    "similarity_score": round(float(score) * 100, 1),
                }
                for score, bug in ranked[:3]
                if float(score) > 0
            ]

            best_score, best_bug = ranked[0]
            similarity_percent = round(float(best_score) * 100, 1)

            if float(best_score) >= threshold:
                is_duplicate = True
                duplicate_of = best_bug.get("id")

    return {
        "is_duplicate": is_duplicate,
        "duplicate_of": duplicate_of,
        "similarity_score": similarity_percent,
        "top_matches": top_matches,
    }
