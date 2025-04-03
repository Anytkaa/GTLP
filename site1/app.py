from flask import Flask, render_template, send_from_directory
import mysql.connector
from config1 import db_config
import base64

app = Flask(__name__, template_folder='templates')

@app.template_filter('b64encode')
def b64encode_filter(data):
    if data is not None:
        return base64.b64encode(data).decode('utf-8')
    return None

def get_country_data(country_id):
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
    crafts = []
    musicalinstruments = []
    dances = []
    literature = []
    cuisine = []
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

        # Запрос для получения информации о ремеслах
        cursor.execute("SELECT name, description, photo FROM crafts WHERE country_id = %s", (country['country_id'],))
        crafts = cursor.fetchall()

        # Запрос для получения информации о музыкальных инструментах
        cursor.execute("SELECT name, photo, information FROM musicalinstruments WHERE country_id = %s",
                       (country['country_id'],))
        musicalinstruments = cursor.fetchall()

        # Запрос для получения информации о танцах
        cursor.execute("SELECT name, description, photo FROM dances WHERE country_id = %s", (country['country_id'],))
        dances = cursor.fetchall()

        # Запрос для получения информации о литературе
        cursor.execute("SELECT name, description, photo FROM literature WHERE country_id = %s",
                       (country['country_id'],))
        literature = cursor.fetchall()

        # Запрос для получения информации о национальной кухне
        cursor.execute(
            "SELECT cuisine_description, ingredients, dish_name, dish_description, dish_photo FROM cuisine WHERE country_id = %s",
            (country['country_id'],))
        cuisine = cursor.fetchall()

        # Запрос для получения информации о достопримечательностях
        cursor.execute("SELECT name, description, photo FROM landmarks WHERE country_id = %s", (country['country_id'],))
        landmarks = cursor.fetchall()

        # Запрос для получения информации для путешествия
        cursor.execute(
            "SELECT behavior, communication, dress_code, taboos, etiquette, phrases FROM travelersinfo WHERE country_id = %s",
            (country['country_id'],))
        travelersinfo = cursor.fetchall()

        # Запрос для получения информации об интересных фактах
        cursor.execute("SELECT information FROM facts WHERE country_id = %s", (country['country_id'],))
        facts = cursor.fetchall()

        # Запрос для получения ссылки на тест
        cursor.execute("""SELECT test_link FROM Tests WHERE country_id = %s
        """, (country['country_id'],))
        Tests = cursor.fetchone()  # Получаем одну запись (или None)

    conn.close()
    return country, locations, flags, coatsofarms, cities, history, historicalFigures, artists, scientists, celebrities, crafts, musicalinstruments, dances, literature, cuisine, landmarks, travelersinfo, facts, Tests

@app.route('/view/<int:country_id>')
def country_view(country_id):
    data = get_country_data(country_id)
    if not data or not data[0]:
        return "Страна не найдена", 404
    
    return render_template(
        'шаблон2.html',
        country=data[0],
        locations=data[1],
        flags=data[2],
        coatsofarms=data[3],
        cities=data[4],
        history=data[5],
        historicalFigures=data[6],
        artists=data[7],
        scientists=data[8],
        celebrities=data[9],
        crafts=data[10],
        musicalinstruments=data[11],
        dances=data[12],
        literature=data[13],
        cuisine=data[14],
        landmarks=data[15],
        travelersinfo=data[16],
        facts=data[17],
        Tests=data[18]
    )

@app.route('/')
def index():
    return send_from_directory('.', 'index.html')

@app.route('/static/<path:filename>')
def static_files(filename):
    return send_from_directory('static', filename)

if __name__ == '__main__':
    app.run(debug=True)