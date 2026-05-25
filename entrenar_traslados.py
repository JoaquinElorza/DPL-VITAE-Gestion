import pandas as pd
from sklearn.linear_model import LinearRegression
import psycopg2


conexion = psycopg2.connect(
    host="localhost",
    user="postgres",
    password="", 
    database="dpl_vitae"
)


query = """
SELECT 
    km_distancia, 
    horas_servicio, 
    oxigeno_lpm, 
    costo_padecimiento_num, 
    CASE WHEN tipo_ambulancia_num = TRUE THEN 1 ELSE 0 END as tipo_ambulancia_num, 
    precio_final
FROM traslados
WHERE es_outlier = false AND usable_para_modelo = true
"""


df = pd.read_sql(query, conexion)

if df.empty:
    print("No hay suficientes datos limpios para entrenar el modelo. Genera datos primero. ")
    conexion.close()
    exit()


X = df[['km_distancia', 'horas_servicio', 'oxigeno_lpm', 'costo_padecimiento_num', 'tipo_ambulancia_num']]
y = df['precio_final']


modelo = LinearRegression()
modelo.fit(X, y)


intercepto = float(modelo.intercept_)
coef_km = float(modelo.coef_[0])
coef_horas = float(modelo.coef_[1])
coef_oxigeno = float(modelo.coef_[2])
coef_padecimiento = float(modelo.coef_[3])
coef_ambulancia = float(modelo.coef_[4])


cursor = conexion.cursor()


cursor.execute("DELETE FROM modelo_traslados")

sql = """
INSERT INTO modelo_traslados (
    b0, b_distancia, b_horas, b_oxigeno, b_padecimiento, b_ambulancia, created_at, updated_at
) VALUES (%s, %s, %s, %s, %s, %s, NOW(), NOW())
"""

cursor.execute(sql, (intercepto, coef_km, coef_horas, coef_oxigeno, coef_padecimiento, coef_ambulancia))
conexion.commit()

cursor.close()
conexion.close()

print("¡Modelo de IA entrenado y coeficientes guardados con éxito!")