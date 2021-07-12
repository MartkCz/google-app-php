build:
	docker build -t martkcz/google-app-php:latest -t martkcz/google-app-php:8.0.8 .

push:
	docker push martkcz/google-app-php:latest
	docker push martkcz/google-app-php:8.0.8
