const express = require('express');
const cors = require('cors');
const tf = require('@tensorflow/tfjs');
// const tfn = require("@tensorflow/tfjs-node"); Deze package kreeg ik niet geinstalleerd. Geen probleem, maar is iets langzamer zonder.
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(express.json());

// Model wordt eenmalig geladen
let model;

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
        // Todo: uncomment onderstaande regel, nadat je getrainde model op de juiste plek hebt gezet.
        // const model = await tf.loadLayersModel('http://localhost:3000/model/model.json');
        console.log('Model loaded successfully.');
    } catch (error) {
        console.error('Error loading model:', error);
        process.exit(1);
    }
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
        const { feature1, feature2, feature3 } = req.body;
        
        // Je kan checken of alle features aanwezig zijn. Hoeft niet per se, maar mocht je het leuk vinden.
            
        
        // Check of model geladen is
        if (!model) {
            return res.status(503).json({ error: 'Model not loaded yet. Please try again later.' });
        }

        // Normaliseer features
        const normalizedFeature1 = [featureNormalize(feature1), featureNormalize(feature2), featureNormalize(feature3)];
        
        // Maak predictie
        const prediction = model.predict(
            tf.tensor1d(normalizedFeature1) // kan zijn dat de shape aangepast moet worden naar het model
        ).arraySync(); // Gebruik arraySync voor kleine datasets

        // Stuur response oftewel onze predictie, etc.
        // Todo: Explainability van model ook terugsturen
        res.json({
            input: { feature1, feature2, feature3 },
            prediction: prediction[0] // Pas aan op basis van de output van je model
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