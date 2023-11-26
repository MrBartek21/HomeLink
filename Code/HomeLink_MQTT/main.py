#!/usr/bin/env python3
from paho.mqtt import client as mqtt_client
import random
from datetime import datetime
import mysql.connector
import time
import json


#sudo apt install python3-pip
#sudo pip install paho-mqtt
#sudo pip3 install mysql-connector


mqtt_host = "10.0.0.193"
mqtt_port = 1883
mqtt_hasAuth = False
mqtt_user = "user"
mqtt_pass = "pass"
mqtt_topic = "homelink"
mqtt_topic_sensors = "SENSOR"
client_id = f'mqtt-{mqtt_topic}-{random.randint(0, 1000)}'

mysql_host = "10.0.0.193"
mysql_user = "root"
mysql_pass = "Bartek2001"
mysql_database = "intelihaven"


# MQTT Reconnecting
FIRST_RECONNECT_DELAY = 1
RECONNECT_RATE = 2
MAX_RECONNECT_COUNT = 12
MAX_RECONNECT_DELAY = 60




def connect_to_database():
    db_config = {
        'host': mysql_host,
        'user': mysql_user,
        'password': mysql_pass,
        'database': mysql_database
    }

    connection = mysql.connector.connect(**db_config)
    return connection

def search_row(Name, NodeID, sql, all):
    connection = connect_to_database()

    try:
        if connection.is_connected():
            cursor = connection.cursor()

            query = "SELECT * FROM Devices WHERE Name = %s AND NodeID = %s"
            value = (Name, NodeID)

            cursor.execute(query, value)
            rows = cursor.fetchall()

            if rows:
                for row in rows:
                    update_row(row[0], all)
            else:
                insert_row(sql, all)

    except mysql.connector.Error as err:
        dt_string = datetime.now().strftime("%d/%m/%Y %H:%M:%S")
        tmp = f"{dt_string} - [Search Row] - Error: {err}"
        print(tmp)

    finally:
        if 'cursor' in locals():
            cursor.close()
        if connection.is_connected():
            connection.close()

def insert_row(sql, values):
    connection = connect_to_database()

    try:
        if connection.is_connected():
            cursor = connection.cursor()

            cursor.execute(sql, values)
            connection.commit()

            dt_string = datetime.now().strftime("%d/%m/%Y %H:%M:%S")
            tmp = f"{dt_string} - [Insert Row] - The row has been successfully added"
            print(tmp)

    except mysql.connector.Error as err:
        dt_string = datetime.now().strftime("%d/%m/%Y %H:%M:%S")
        tmp = f"{dt_string} - [Insert Row] - Error: {err}"
        print(tmp)

    finally:
        if 'cursor' in locals():
            cursor.close()
        if connection.is_connected():
            connection.close()

def update_row(row_id, all):
    connection = connect_to_database()

    try:
        if connection.is_connected():
            cursor = connection.cursor()

            query = "UPDATE Devices SET Value = %s, Date = %s, RSSI = %s WHERE ID = %s"
            data = (all[11], all[12], all[7], row_id)

            cursor.execute(query, data)
            connection.commit()

            dt_string = datetime.now().strftime("%d/%m/%Y %H:%M:%S")
            tmp = f"{dt_string} - [Update Row] - The row has been successfully updated"
            print(tmp)

    except mysql.connector.Error as err:
        dt_string = datetime.now().strftime("%d/%m/%Y %H:%M:%S")
        tmp = f"{dt_string} - [Update Row] - Error: {err}"
        print(tmp)

    finally:
        if 'cursor' in locals():
            cursor.close()
        if connection.is_connected():
            connection.close()




# MQTT Connect
def connect_mqtt() -> mqtt_client:
    def on_connect(client, userdata, flags, rc):
        dt_string = datetime.now().strftime("%d/%m/%Y %H:%M:%S")
        if rc == 0:
            tmp = f"{dt_string} - [Connect] Connected to MQTT Broker!"
            print(tmp)
        else:
            tmp = f"{dt_string} - [Connect] Failed to connect, return code {rc}"
            print(tmp)

    client = mqtt_client.Client(client_id)
    if mqtt_hasAuth == True:
        client.username_pw_set(mqtt_user, mqtt_pass)

    client.on_connect = on_connect
    client.on_disconnect = on_disconnect
    client.connect(mqtt_host, mqtt_port)
    return client

def on_disconnect(client, userdata, rc):
    dt_string = datetime.now().strftime("%d/%m/%Y %H:%M:%S")
    print(f"{dt_string} - [Disconnect] Disconnected with result code: {rc}")

    reconnect_count, reconnect_delay = 0, FIRST_RECONNECT_DELAY
    while reconnect_count < MAX_RECONNECT_COUNT:
        dt_string = datetime.now().strftime("%d/%m/%Y %H:%M:%S")
        print(f"{dt_string} - [Disconnect] Reconnecting in {reconnect_delay} seconds...")

        time.sleep(reconnect_delay)
        try:
            client.reconnect()
            dt_string = datetime.now().strftime("%d/%m/%Y %H:%M:%S")
            print(f"{dt_string}- [Disconnect] Reconnected successfully!")
            return
        except Exception as err:
            dt_string = datetime.now().strftime("%d/%m/%Y %H:%M:%S")
            print(f"{dt_string} - [Disconnect] {err}. Reconnect failed. Retrying...")

        reconnect_delay *= RECONNECT_RATE
        reconnect_delay = min(reconnect_delay, MAX_RECONNECT_DELAY)
        reconnect_count += 1

    dt_string = datetime.now().strftime("%d/%m/%Y %H:%M:%S")
    print(f"{dt_string} - [Disconnect] Reconnect failed after {reconnect_count} attempts. Exiting...")

def subscribe(client: mqtt_client):
    def on_message(client, userdata, msg):
        dt_string = datetime.now().strftime("%d/%m/%Y %H:%M:%S")
        mqttMessage = msg.payload.decode()
        tmp = f"{dt_string} - [Subscribe] Received msg [{mqttMessage}] from topic [{msg.topic}]"
        print(tmp)

        topic = msg.topic.split("/")
        if len(topic) > 1:
            if topic[2] == mqtt_topic_sensors:
                Description = "" # - BRAK ZMIAN - Opis czujnika
                LocationID = 0 # - BRAK ZMIAN - Domyślna lokalizacja tego czujnika 0 = nieustalone do żadnego
                FriendlyName = "" # - BRAK ZMIAN - Nazwa nadana przez użytkownika
                Protocol = "MQTT" # - BRAK ZMIAN - Protokół jakim te dane były przesłane do tego skryptu
                

                Reciver = topic[1] #Urządzenie które odebrało te dane i przekazało do bazy danych

                json_obj = json.loads(mqttMessage)
                Date = json_obj['Time'] #Date - Kiedy ostatnia aktualizacja czujnika


                for key, value in json_obj.items():
                    if key == 'Time' or key == 'TempUnit':
                        continue

                    NodeName = key #Nazwa urządzenia które daje te sensory
                    data_array = []
                    if 'mac' in value:
                        MAC = value['mac'] #MAC
                        NodeID = MAC[:5] #NodeID
                    else:
                        MAC = 0
                        NodeID = 0 #NodeID

                    if 'RSSI' in value:
                        RSSI = value['RSSI'] #Value
                    else:
                        RSSI = 1

                    if 'Temperature' in value:
                        Temperature = value['Temperature'] #Value
                        data_array.append({'SensorType': 'Temperature', 'Value': Temperature})
                    else:
                        Temperature = -256

                    if 'Humidity' in value:
                        Humidity = value['Humidity'] #Value
                        data_array.append({'SensorType': 'Humidity', 'Value': Humidity})
                    else:
                        Humidity = -256

                    if 'Battery' in value:
                        Battery = value['Battery'] #Value
                        data_array.append({'SensorType': 'Battery', 'Value': Battery})
                    else:
                        Battery = -256

                    
                
                

                if RSSI and RSSI < 0:
                    comm = "BLE"
                else:
                    #comm = json_obj['Communication']
                    comm = "N/A"
                Communication = comm #Jaka komunikacja została użyta aby te dane odebrać z urządzenia z czujnikami np NRF, BLE, RF, API

                for item in data_array:
                    sensor_type = item['SensorType']
                    value = item['Value']

                    sql = "INSERT INTO Devices (Name, NodeID, NodeName, Reciver, Protocol, Communication, MAC, RSSI, Description, LocationID, FriendlyName, Value, Date) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
                    val = (sensor_type, NodeID, NodeName, Reciver, Protocol, Communication, MAC, RSSI, Description, LocationID, FriendlyName, value, Date)

                    search_row(sensor_type, NodeID, sql, val)

                #print(json_obj)

    client.subscribe("#")
    client.on_message = on_message



if __name__ == '__main__':
    # dd/mm/YY H:M:S
    dt_string = datetime.now().strftime("%d/%m/%Y %H:%M:%S")
    print(dt_string, "- Starting Python MQTT Service")

    client = connect_mqtt()
    subscribe(client)
    client.loop_forever()
