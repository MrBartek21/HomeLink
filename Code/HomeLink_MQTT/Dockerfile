FROM python:3.9-alpine
#FROM base as builder

RUN pip3 install paho-mqtt
RUN pip3 install mysql-connector-python



# Ustawiamy katalog roboczy
WORKDIR /app

# Kopiujemy plik main.py do katalogu /app w kontenerze
COPY main.py /app/

#FROM base
# copy only the dependencies installation from the 1st stage image
#COPY --from=builder /root/.local /root/.local

#ADD mqtt.py .


# Set one or more individual labels

LABEL name="HomeLink MQTT"
LABEL author="Bartek Pacyna"
LABEL maintainer="Bartek Pacyna"
LABEL description="Aplikacja Python z obsługą MQTT i loggera"

LABEL pl.hl_mqtt.version="2023.11.26"
LABEL pl.hl_mqtt.release-date="2023-11-26"
LABEL pl.hl_mqtt.version.is-production="0"


HEALTHCHECK --interval=30s --timeout=3s \
  CMD python3 print("Healthy") || exit 1

ENV PATH=/home/app/.local/bin:$PATH
CMD ["python3", "mqtt.py"]