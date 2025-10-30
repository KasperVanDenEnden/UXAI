import numpy as np
import pandas as pd
import tensorflow as tf
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
import json
import os

# Definieer de bestandsnaam en de doelvariabele
FILE_NAME = 'hair_problem.csv' # Pas dit pad aan!
TARGET_COLUMN = 'Do you have hair fall problem ?'

# **DE 5 GEVRAAGDE KENMERKEN**
FEATURE_COLUMNS = [
    'Do you have too much stress',
    'Do you think that in your area water is a reason behind hair fall problems?',
    'Do you have any type of sleep disturbance?',
    'Do you use chemicals, hair gel, or color in your hair?',
    'Is there anyone in your family having a hair fall problem or a baldness issue?'
]

# Data laden en filteren
try:
    df = pd.read_csv(FILE_NAME)
except FileNotFoundError:
    print(f"Fout: Bestand '{FILE_NAME}' niet gevonden. Pas het pad aan.")
    exit()

X = df[FEATURE_COLUMNS].copy()
y = df[TARGET_COLUMN].copy()

# Data Codering (Alle 5 zijn binaire 'Ja'/'Nee')
def map_yes_no(value):
    return 1 if value == 'Yes' else 0

for col in FEATURE_COLUMNS:
    if col in X.columns:
        X.loc[:, col] = X[col].apply(map_yes_no).astype(np.float32) # Gebruik float32 voor TF
y = y.apply(map_yes_no).astype(np.float32)

# Splits de data
X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42
)

# Scaling (CRUCIAAL)
scaler = StandardScaler()
X_train_scaled = scaler.fit_transform(X_train)
# Let op: Keras heeft een NumPy array nodig, niet een DataFrame
X_train_np = X_train_scaled.astype(np.float32)
y_train_np = y_train.values.astype(np.float32)

# =========================================================
# 💥 STAP A: TensorFlow/Keras Model Definitie
# Logistische Regressie is een neuraal netwerk met één neuron en een sigmoid.
# =========================================================
num_features = len(FEATURE_COLUMNS)

model_tf = tf.keras.Sequential([
    tf.keras.layers.InputLayer(input_shape=(num_features,)),
    tf.keras.layers.Dense(1, activation='sigmoid', name='logistic_output')
])

# Logistische Regressie gebruikt Binary Crossentropy
model_tf.compile(
    optimizer='adam',
    loss='binary_crossentropy',
    metrics=['accuracy']
)

print("\nKeras Model Samenvatting:")
model_tf.summary()

# Training (korte training is vaak voldoende voor LR)
print("\nTraining Keras Model...")
model_tf.fit(
    X_train_np,
    y_train_np,
    epochs=50,
    batch_size=32,
    verbose=0
)
print("✅ Keras Model Getraind.")

# =========================================================
# 💥 STAP B: Export Scaling Parameters
# =========================================================
export_dir = './model_tfjs'

# Scaling parameters moeten apart worden geëxporteerd omdat ze NIET in het Keras-model zitten.
scaling_params = {
    "feature_names": FEATURE_COLUMNS,
    "scaling_params": {
        "mean": scaler.mean_.tolist(),
        "std": scaler.scale_.tolist()
    }
}

# Sla scaling parameters op als JSON
if not os.path.exists(export_dir):
    os.makedirs(export_dir)
with open(os.path.join(export_dir, 'scaling_params.json'), 'w') as f:
    json.dump(scaling_params, f, indent=4)
print(f"✅ Scaling parameters opgeslagen in {export_dir}/scaling_params.json")

# =========================================================
# 💥 STAP C: Export Keras Model naar TensorFlow.js-formaat
# =========================================================
import tensorflowjs as tfjs

try:
    tfjs.converters.save_keras_model(model_tf, export_dir)
    print(f"✅ TensorFlow.js Model succesvol geëxporteerd naar de map: {export_dir}")
    print("Inhoud: model.json en gewichtsbestanden (bin-bestanden).")
except Exception as e:
    print(f"Fout bij exporteren: Zorg ervoor dat 'tensorflowjs' is geïnstalleerd. {e}")
