const express = require('express');
const cors = require('cors');
// const tf = require('@tensorflow/tfjs'); 
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// =================================================================
// 💥 STAP 1: PLAATS HIER DE JSON OUTPUT VAN DE PYTHON CODE
// =================================================================
const MODEL_PARAMS = {
    {
    "feature_names": [
        "Do you have too much stress",
        "Do you think that in your area water is a reason behind hair fall problems?",
        "Do you have any type of sleep disturbance?",
        "Do you use chemicals, hair gel, or color in your hair?",
        "Is there anyone in your family having a hair fall problem or a baldness issue?"
    ],
    "model_type": "LogisticRegression (scikit-learn)",
    "coefficients": [
        0.5938073810363358,
        0.6922506833083077,
        0.45148292744846513,
        0.6017728120259745,
        0.500911110844722
    ],
    "intercept": 1.0286864998223022,
    "scaling_params": {
        "mean": [
            0.722027972027972,
            0.5979020979020979,
            0.5576923076923077,
            0.6468531468531469,
            0.7167832167832168
        ],
        "std": [
            0.44799953084478344,
            0.49032150597987034,
            0.49666044500557527,
            0.47794785621375907,
            0.45056080269051463
        ]
    }
};
// =================================================================

// Middleware
app.use(cors());
app.use(express.json());

// Functie om de Z-score (Normalisatie) te berekenen
function featureNormalize(value, mean, std) {
    return (value - mean) / std;
}

// Logistische Regressie Predictie Functie
function predictLogisticRegression(features) {
    const { coefficients, intercept, scaling_params, feature_names } = MODEL_PARAMS;
    
    // Stap 1: Bereken de lineaire som (Z = W*X + B)
    let linearSum = intercept;
    
    for (let i = 0; i < feature_names.length; i++) {
        const featureName = feature_names[i];
        const input_value = features[featureName];

        // Normaliseer de input waarde
        const normalized_value = featureNormalize(
            input_value, 
            scaling_params.mean[i], 
            scaling_params.std[i]
        );
        
        // Accumuleer de gewogen som
        linearSum += coefficients[i] * normalized_value;
    }

    // Stap 2: Pas de Sigmoid functie toe: P = 1 / (1 + e^(-Z))
    const probability = 1 / (1 + Math.exp(-linearSum));
    
    return probability;
}

// Health check endpoint
app.get('/api/health', (req, res) => {
    res.json({ status: 'ok', modelLoaded: true });
});

// Prediction endpoint (Logistische Regressie)
app.post('/api/predict', async (req, res) => {
    try {
        // De input features zijn de binaire waarden (0 of 1) voor de 5 kenmerken
        const {
            'Do you have too much stress': stress,
            'Do you think that in your area water is a reason behind hair fall problems?': water,
            'Do you have any type of sleep disturbance?': sleep,
            'Do you use chemicals, hair gel, or color in your hair?': chemicals,
            'Is there anyone in your family having a hair fall problem or a baldness issue?': family
        } = req.body;

        const inputFeatures = {
            'Do you have too much stress': stress,
            'Do you think that in your area water is a reason behind hair fall problems?': water,
            'Do you have any type of sleep disturbance?': sleep,
            'Do you use chemicals, hair gel, or color in your hair?': chemicals,
            'Is there anyone in your family having a hair fall problem or a baldness issue?': family
        };
        
        // Controleer of alle 5 features aanwezig en binair zijn (0 of 1)
        const isInputValid = Object.values(inputFeatures).every(val => val === 0 || val === 1);
        
        if (!isInputValid) {
             return res.status(400).json({ error: 'Input features must be present and have a binary value (0 or 1).' });
        }

        // Maak predictie
        const predictionProbability = predictLogisticRegression(inputFeatures);
        const predictionClass = predictionProbability >= 0.5 ? 1 : 0; // 0.5 is de standaarddrempel

        // Stuur response
        res.json({
            input: inputFeatures,
            probability_hairfall: parseFloat(predictionProbability.toFixed(4)),
            prediction_class: predictionClass,
            message: predictionClass === 1 ? 'Voorspelling: Haaruitval' : 'Voorspelling: Geen Haaruitval'
        });

    } catch (error) {
        console.error('Error during prediction:', error);
        res.status(500).json({ error: 'Prediction failed', message: error.message });
    }
});

// Start Server
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
    console.log(`Test POST http://localhost:${PORT}/api/predict met JSON body:`);
    console.log(
        JSON.stringify({
            'Do you have too much stress': 1,
            'Do you think that in your area water is a reason behind hair fall problems?': 1,
            'Do you have any type of sleep disturbance?': 0,
            'Do you use chemicals, hair gel, or color in your hair?': 0,
            'Is there anyone in your family having a hair fall problem or a baldness issue?': 1
        }, null, 2)
    );
});
