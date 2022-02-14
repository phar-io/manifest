FROM debian:buster

RUN apt update \
    && apt-get install -y --no-install-recommends ant php-cli php-dom php-curl php-mbstring wget gpg dirmngr gpg-agent

ENTRYPOINT ["ant"]
