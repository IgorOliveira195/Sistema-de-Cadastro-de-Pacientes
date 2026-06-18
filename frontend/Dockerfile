FROM node:18-alpine

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .

ENV VUE_APP_API_URL=/api

CMD ["sh", "-c", "npm install && tail -f /dev/null"]
