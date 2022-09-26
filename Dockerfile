FROM webdevops/php-nginx:8.1

RUN set -eux; \
    apt-get update; \
    apt-get install -y apt-utils tesseract-ocr poppler-utils; \
    rm -rf /var/lib/apt/lists/*;
