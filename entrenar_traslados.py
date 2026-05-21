import pandas as pd
from sklearn.linear_model import LinearRegression
import mysql.connector

# conexión mysql
conexion = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="dpl_vitae"
)

# consulta
query = """
SELECT
km_distancia,
horas_servicio,
oxigeno_lpm,
costo_padecimiento_num,
tipo_ambulancia_num,
num_paramedicos,
precio_final
FROM traslados
"""

# dataframe
df = pd.read_sql(query, conexion)

# variables independientes
X = df[
    [
        'km_distancia',
        'horas_servicio',
        'oxigeno_lpm',
        'costo_padecimiento_num',
        'tipo_ambulancia_num',
        'num_paramedicos'
    ]
]

# variable objetivo
y = df['precio_final']

# entrenar modelo
modelo = LinearRegression()
modelo.fit(X, y)

# obtener coeficientes
coeficientes = modelo.coef_
intercepto = modelo.intercept_

cursor = conexion.cursor()

# limpiar modelo anterior
cursor.execute("DELETE FROM modelo_traslados")

# insertar modelo
sql = """
INSERT INTO modelo_traslados (
intercepto,
coef_km_distancia,
coef_horas_servicio,
coef_oxigeno_lpm,
coef_costo_padecimiento,
coef_tipo_ambulancia,
coef_num_paramedicos,
created_at,
updated_at
)
VALUES (%s,%s,%s,%s,%s,%s,%s,NOW(),NOW())
"""

valores = (
float(intercepto),
float(coeficientes[0]),
float(coeficientes[1]),
float(coeficientes[2]),
float(coeficientes[3]),
float(coeficientes[4]),
float(coeficientes[5]),
)

cursor.execute(sql, valores)

conexion.commit()

print("Modelo entrenado correctamente")