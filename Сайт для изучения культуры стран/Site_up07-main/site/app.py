from flask import Flask, render_template
import mysql.connector
from config1 import db_config

app = Flask(__name__)

def get_country_data():
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    cursor.execute("SELECT * FROM Countries WHERE name = %s", ("Египет",))
    country = cursor.fetchone()

    locations = []
    flags = {}
    coatsofarms = {}
    cities = []
    history = []
    historicalFigures = []
    artists = []
    scientists = []
    celebrities = []
    dances = []
    literature = []
    landmarks = []
    travelersinfo = []
    facts = []

    # Если страна найдена, получаем связанные данные из Locations
    if country:
        cursor.execute("""
                SELECT official_name, description, population, official_language, 
                       religion, president, photo, area_km2, capital
                FROM Locations WHERE country_id = %s
            """, (country['country_id'],))
        locations = cursor.fetchall()

        # Запрос для получения информации о крупных городах 
        cursor.execute("SELECT name, photo FROM cities WHERE country_id = %s", (country['country_id'],))
        cities = cursor.fetchall()
        
        # Запрос для получения информации о флаге
        cursor.execute("SELECT information, photo FROM flags WHERE country_id = %s", (country['country_id'],))
        flags = cursor.fetchone()

        # Запрос для получения информации о гербе
        cursor.execute("SELECT description, photo FROM coatsofarms WHERE country_id = %s", (country['country_id'],))
        coatsofarms = cursor.fetchone()

        # Запрос для получения информации об истории
        cursor.execute("SELECT event, description FROM history WHERE country_id = %s", (country['country_id'],))
        history = cursor.fetchall()

        # Запрос для получения информации о исторических личностях
        cursor.execute("""
            SELECT full_name, lifespan, information, photo 
            FROM famouspeople 
            WHERE country_id = %s AND type_id = 1
        """, (country['country_id'],))
        historicalFigures = cursor.fetchall()

        # Запрос для получения информации о деятелях искусства
        cursor.execute("""
            SELECT full_name, lifespan, information, photo 
            FROM famouspeople 
            WHERE country_id = %s AND type_id = 2
        """, (country['country_id'],))
        artists = cursor.fetchall()

        # Запрос для получения информации об ученых и изобретателей
        cursor.execute("""
            SELECT full_name, lifespan, information, photo 
            FROM famouspeople 
            WHERE country_id = %s AND type_id = 3
        """, (country['country_id'],))
        scientists = cursor.fetchall()

        # Запрос для получения информации о современных знаменитостей
        cursor.execute("""
            SELECT full_name, lifespan, information, photo 
            FROM famouspeople 
            WHERE country_id = %s AND type_id = 4
        """, (country['country_id'],))
        celebrities = cursor.fetchall()

        # Запрос для получения информации о танцах
        cursor.execute("SELECT name, description, photo FROM dances WHERE country_id = %s", (country['country_id'],))
        dances = cursor.fetchall()
        
        # Запрос для получения информации о литературе
        cursor.execute("SELECT name, description, photo FROM literature WHERE country_id = %s", (country['country_id'],))
        literature = cursor.fetchall()

        # Запрос для получения информации о достопримечательностях
        cursor.execute("SELECT name, description, photo FROM landmarks WHERE country_id = %s", (country['country_id'],))
        landmarks = cursor.fetchall()
        
        # Запрос для получения информации для путешествия
        cursor.execute("SELECT behavior, communication, dress_code, taboos, etiquette, phrases FROM travelersinfo WHERE country_id = %s", (country['country_id'],))
        travelersinfo = cursor.fetchall()

        # Запрос для получения информации об интересных фактах
        cursor.execute("SELECT information FROM facts WHERE country_id = %s", (country['country_id'],))
        facts = cursor.fetchall()

    conn.close()
    return country, locations,  flags, coatsofarms, cities, history, historicalFigures, artists, scientists, celebrities, dances, literature, landmarks, travelersinfo, facts

@app.route('/')
def index():
    country, locations, flags, coatsofarms, cities, history, historicalFigures, artists, scientists, celebrities, dances, literature, landmarks, travelersinfo, facts = get_country_data()
    return render_template('шаблон1.html', country=country, locations=locations,flags=flags, coatsofarms=coatsofarms, cities=cities, history=history, historicalFigures=historicalFigures, artists=artists, scientists=scientists, celebrities=celebrities, dances=dances, literature=literature, landmarks=landmarks, travelersinfo=travelersinfo, facts=facts)

if __name__ == '__main__':
    app.run(debug=True)
