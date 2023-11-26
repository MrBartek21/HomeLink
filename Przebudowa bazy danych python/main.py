import mysql.connector

# Parametry do połączenia z bazą danych


def connect_to_database():
    # Parametry do połączenia z bazą danych
    db_config = {
        'host': '10.0.0.193',
        'user': 'root',
        'password': 'Bartek2001',
        'database': 'intelihaven'
    }

    # Tworzenie połączenia
    connection = mysql.connector.connect(**db_config)
    return connection


def select_all_rows():
    connection = connect_to_database()

    try:
        if connection.is_connected():
            cursor = connection.cursor()

            # Zapytanie SQL do pobrania wszystkich wierszy z danej kolumny
            query = "SELECT * FROM historical_data WHERE ID>1745000"

            # Wykonanie zapytania
            cursor.execute(query)

            # Pobranie wszystkich wyników
            rows = cursor.fetchall()

             # Wyświetlenie wyników
            for row in rows:
                if row[4] == "HUMIDITY_DHT":
                    send(row)
                elif row[4] == "TEMP_DHT":
                    send(row)
                elif row[4] == "PASCAL_BMP":
                    send(row)
                elif row[4] == "TEMP_BMP":
                    send(row)
                elif row[4] == "CPPM_MQ135":
                    send(row)
                elif row[4] == "TEMP_DALLAS":
                    send(row)
                elif row[4] == "LDR":
                    send(row)
                elif row[4] == "RAIN":
                    send(row)
                else:
                    print(row)

    except mysql.connector.Error as err:
        print(f"Błąd: {err}")

    finally:
        # Zamknięcie kursora i połączenia
        if 'cursor' in locals():
            cursor.close()
        if connection.is_connected():
            connection.close()
            #print("Połączenie z bazą danych zostało zamknięte.")



def search_row(column_value, all):
    connection = connect_to_database()

    try:
        if connection.is_connected():
            cursor = connection.cursor()

            # Zapytanie SQL do wyszukania wiersza na podstawie wartości w kolumnie
            query = "SELECT * FROM Devices WHERE Name = %s"
            value = (column_value,)

            # Wykonanie zapytania
            cursor.execute(query, value)

            # Pobranie wyników
            rows = cursor.fetchall()

            # Wyświetlenie wyników
            if rows:
                #print("Znaleziono wiersz:")
                print("Działanie na wierszu numer: "+str(all[0])+" UPDATE")
                for row in rows:
                    #print(row)
                    update_row(row[0], all[5], all[6])
            else:
                #print("Nie znaleziono wiersza o podanej wartości w kolumnie.")
                nowy_wiersz = (all[4], all[1], all[2], "MQTT", all[3], '', '', all[5], all[6])
                #print(nowy_wiersz)
                print("Działanie na wierszu numer: "+str(all[0])+" INSERT")
                insert_row(nowy_wiersz)

    except mysql.connector.Error as err:
        print(f"Błąd: {err}")

    finally:
        # Zamknięcie kursora i połączenia
        if 'cursor' in locals():
            cursor.close()
        if connection.is_connected():
            connection.close()
            #print("Połączenie z bazą danych zostało zamknięte.")

def insert_row(values):
    connection = connect_to_database()

    try:
        if connection.is_connected():
            cursor = connection.cursor()

            # Zapytanie SQL do wstawiania wiersza do tabeli
            query = "INSERT INTO Devices (Name, NodeID, NodeName, Protocol, Communication, Description, FriendlyName, Value, Date) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)"

            # Wykonanie zapytania
            cursor.execute(query, values)

            # Potwierdzenie zmian w bazie danych
            connection.commit()

            print("Wiersz został pomyślnie dodany.")

    except mysql.connector.Error as err:
        print(f"Błąd: {err}")

    finally:
        # Zamknięcie kursora i połączenia
        if 'cursor' in locals():
            cursor.close()
        if connection.is_connected():
            connection.close()
            #print("Połączenie z bazą danych zostało zamknięte.")

def update_row(row_id, new_value, new_date):
    connection = connect_to_database()

    try:
        if connection.is_connected():
            cursor = connection.cursor()

            # Zapytanie SQL do aktualizacji wartości w kolumnie Value oraz Date
            query = "UPDATE Devices SET Value = %s, Date = %s WHERE ID = %s"
            data = (new_value, new_date, row_id)

            # Wykonanie zapytania
            cursor.execute(query, data)

            # Potwierdzenie zmian w bazie danych
            connection.commit()

            #print(str(row_id)+" Wiersz został pomyślnie zaktualizowany.")

    except mysql.connector.Error as err:
        print(f"Błąd: {err}")

    finally:
        # Zamknięcie kursora i połączenia
        if 'cursor' in locals():
            cursor.close()
        if connection.is_connected():
            connection.close()
            #print("Połączenie z bazą danych zostało zamknięte.")



def send(data):
        #print(data)
        search_row(data[4], data)

# Przykład użycia
select_all_rows()
