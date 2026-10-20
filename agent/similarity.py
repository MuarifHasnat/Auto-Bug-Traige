import math
import re
from collections import Counter


def cosine_similarity(text, candidates):
    def vectorize(value):
        tokens = re.findall(r"[a-z0-9]+", value.lower())
        counts = Counter(tokens)
        total = sum(counts.values()) or 1
        return {token: count / total for token, count in counts.items()}

    source = vectorize(text)
    source_norm = math.sqrt(sum(value * value for value in source.values()))
    scores = []
    for candidate in candidates:
        target = vectorize(candidate)
        target_norm = math.sqrt(sum(value * value for value in target.values()))
        dot = sum(source.get(token, 0) * value for token, value in target.items())
        scores.append(dot / (source_norm * target_norm) if source_norm and target_norm else 0.0)
    return scores


def detect_duplicates(title, description, past_bugs, threshold=0.50):
    is_duplicate = False
    duplicate_of = None
    similarity_percent = 0.0

    if past_bugs and len(past_bugs) > 0:
        all_texts = [b.get("title", "") + " " + b.get("description", "") for b in past_bugs]
        current_text = title + " " + description

        scores = cosine_similarity(current_text, all_texts)
        if scores:
            best_score = max(scores)
            max_index = scores.index(best_score)
            similarity_percent = round(best_score * 100, 1)

            if best_score >= threshold:
                is_duplicate = True
                duplicate_of = past_bugs[max_index].get("id")

    return {
        "is_duplicate": is_duplicate,
        "duplicate_of": duplicate_of,
        "similarity_score": similarity_percent,
    }
