const express = require('express');
const path = require('path');
const app = express();
const PORT = process.env.PORT || 3000;

const voyages = [
    { id: 1, destination: 'Kribi, Sud', prix: '12000 fcfa', places: 5 },
    { id: 2, destination: 'Ngaoundéré, Adamaoua', prix: '85000 fcfa', places: 2 },
    { id: 3, destination: 'Tokyo, Japon', prix: '1500€', places: 8 }
];

app.use(express.json());
app.use(express.static(path.join(__dirname)));

app.get('/api/voyages', (req, res) => {
    res.json(voyages);
});

app.post('/api/reserver', (req, res) => {
    const { id, client } = req.body;
    const voyage = voyages.find(v => v.id === id);

    if (!voyage) {
        return res.status(404).json({ error: 'Voyage introuvable' });
    }
    if (voyage.places <= 0) {
        return res.status(400).json({ error: 'Plus de places disponibles' });
    }

    voyage.places -= 1;
    return res.json({ message: `Réservation confirmée pour ${client} à ${voyage.destination}` });
});

app.listen(PORT, () => {
    console.log(`Serveur lancé sur http://localhost:${PORT}`);
});
