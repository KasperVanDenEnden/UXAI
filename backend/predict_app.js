const express = require('express');
const cors = require('cors');
const tf = require('@tensorflow/tfjs');
const ort = require('onnxruntime-node');
const fs = require('fs');
// const tfn = require("@tensorflow/tfjs-node"); Deze package kreeg ik niet geinstalleerd. Geen probleem, maar is iets langzamer zonder.
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(express.json());

// Model wordt eenmalig geladen
let model;
let modelWeights;

// Feature normalization parameters (Todo: pas aan naar jouw model)
const TRAINING_SET_MEAN = 8.15
const TRAINING_SET_STD = 1.06

// Functie om features te normaliseren
// Todo: Pas aan naar ons model
function featureNormalize(value) {
    return (value - TRAINING_SET_MEAN) / TRAINING_SET_STD;
}

// load model bij server start
async function loadModel() {
    try {
        console.log('Loading pre-trained model...');
        const session = await ort.InferenceSession.create('./model/hair_fall_model.onnx');
        model = session;
        console.log('ONNX model loaded successfully.');
        console.log('Input names:', model.inputNames);
        console.log('Output names:', model.outputNames);
        // fetch weights
        modelWeights = JSON.parse(fs.readFileSync('./model/model_weights.json', 'utf8'));
        console.log('Model weights loaded successfully.');
        console.log(modelWeights);

    } catch (error) {
        console.error('Error loading model:', error);
        process.exit(1);
    }
}

function calculateFeatureContributions(normalizedFeatures) {
    const { coefficients, bias, feature_names} = modelWeights;

    // Calculate contributions
    const contributions = normalizedFeatures.map((value, index) => ({
        feature: feature_names[index],
        value: value,
        coefficients: coefficients[index],
        contribution: value * coefficients[index]
    }));

    // Sort contributions (most impactful first)
    const sortedContributions = [...contributions].sort((a, b) => Math.abs(b.contribution) - Math.abs(a.contribution));

    const logit = contributions.reduce((sum, item) => sum + item.contribution, 0) + bias;
    const probability = 1 / (1 + Math.exp(-logit));
    
    return {
        contributions: sortedContributions,
        bias: bias,
        logit: logit,
        probability: probability,
        mostInfluential: sortedContributions[0].feature,
        summary: "The feature '" + sortedContributions[0].feature + "' has the highest impact on the prediction."
    };
}

// Health check endpoint
app.get('/api/health', (req, res) => {
    res.json({ 
        status: 'ok', 
        modelLoaded: !!model 
    });
});

// prediction endpoint
app.post('/api/predict', async (req, res) => {
    try {
        // request (req) is de body die ik mee kan sturen naar deze route. 
        // Dan blijft de route hetzelfde, maar de body kan dus verschillen ipv dat we de features in de url zetten.
        const { stress, water_reason , sleep_disturbance, hair_chemicals, family_history } = req.body;
        
        // Check of alle features aanwezig zijn
        if (stress === undefined || water_reason === undefined || sleep_disturbance === undefined || hair_chemicals === undefined || family_history === undefined) {
            return res.status(400).json({ 
                error: 'Missing features. Please provide stress, water_reason, sleep_disturbance, hair_chemicals, and family_history' 
            });
        }

        // Check of model geladen is
        if (!model) {
            return res.status(503).json({ error: 'Model not loaded yet. Please try again later.' });
        }

        // Normaliseer features
        const normalizedFeatures = [
                featureNormalize(stress),
                featureNormalize(water_reason),
                featureNormalize(sleep_disturbance),
                featureNormalize(hair_chemicals),
                featureNormalize(family_history)
            ];
        
        const inputTensor = new ort.Tensor('float32', Float32Array.from(normalizedFeatures), [1, 5]);    

        const feeds = {};
        feeds[model.inputNames[0]] = inputTensor;

        const results = await model.run(feeds);
        const output = results[model.outputNames[0]];
        const prediction = output.data;

        // Stuur response oftewel onze predictie, etc.
        // Todo: Explainability van model ook terugsturen
        res.json({
            input: { stress, water_reason, sleep_disturbance, hair_chemicals, family_history },
            normalized: normalizedFeatures,
            prediction: prediction[0],
            risk_level: prediction[0] > 0.7 ? 'High Risk' : 'Low Risk',
            explainability: calculateFeatureContributions(normalizedFeatures) 
        })
    } catch (error) {
        console.error('Error during prediction:', error);
        res.status(500).json({ 
            error: 'Prediction failed',
            message: error.message 
        });
    }
});

// Start Server
loadModel().then(() => {
    app.listen(PORT, () => {
        console.log(`Server running on http://localhost:${PORT}`);
        console.log(`Test: POST http://localhost:${PORT}/api/predict`);
    });
});