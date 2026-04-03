<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PassionFroid - Outil de Pilotage Tarifaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --passion-purple: #6B2D82; }
        .bg-purple-pf { background-color: var(--passion-purple); }
        .text-purple-pf { color: var(--passion-purple); }
        .border-purple-pf { border-color: var(--passion-purple); }
        tr:nth-child(even) { background-color: #f8f4f9; }
    </style>
</head>
<body class="bg-gray-100 font-sans">

    <header class="bg-white shadow-md p-4">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-48 p-2 border rounded">
                    <p class="text-xs font-bold text-center text-purple-pf uppercase">PassionFroid</p>
                    <p class="text-[10px] text-center text-blue-900 font-bold uppercase tracking-tighter">groupe pomona</p>
                </div>
                <h1 class="text-xl md:text-2xl font-bold text-purple-pf ml-4">OUTIL DE PILOTAGE TARIFAIRE</h1>
            </div>
            <div class="mt-4 md:mt-0 text-right">
                <span class="bg-purple-pf text-white px-4 py-2 rounded-full text-sm font-semibold shadow-sm">Version 2026 - Restaurant App</span>
            </div>
        </div>
    </header>

    <main class="container mx-auto p-4 md:p-8">
        
        <section class="bg-white rounded-xl shadow-lg p-6 mb-8 border-t-4 border-purple-pf">
            <h2 class="text-lg font-bold mb-4 text-gray-700 flex items-center">
                <span class="mr-2">➕</span> Ajouter un nouveau plat / Simulation
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nom du produit</label>
                    <input type="text" id="new-name" placeholder="Ex: Entrecôte" class="w-full border rounded p-2 focus:ring-2 focus:ring-purple-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Prix Achat HT / KG</label>
                    <input type="number" id="new-price" step="0.01" class="w-full border rounded p-2 focus:ring-2 focus:ring-purple-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Grammage Portion (g)</label>
                    <input type="number" id="new-weight" class="w-full border rounded p-2 focus:ring-2 focus:ring-purple-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Prix Vente TTC Souhaité</label>
                    <input type="number" id="new-sale" step="0.01" class="w-full border rounded p-2 focus:ring-2 focus:ring-purple-400 outline-none">
                </div>
                <div class="md:col-span-4 mt-2">
                    <button onclick="addProduct()" class="bg-purple-pf text-white font-bold py-2 px-6 rounded-lg hover:opacity-90 transition shadow-md w-full md:w-auto">
                        Calculer et Ajouter au tableau
                    </button>
                </div>
            </div>
        </section>

        <section class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-purple-pf">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-purple-pf text-white uppercase text-[11px] tracking-wider">
                        <tr>
                            <th class="p-4 border-r border-purple-400">Produit</th>
                            <th class="p-4">Tarif Achat KG</th>
                            <th class="p-4">Grammage Net</th>
                            <th class="p-4">Coût Portion (Net)</th>
                            <th class="p-4">Garniture (30%)</th>
                            <th class="p-4 font-bold bg-purple-900">Coût Assiette TTC</th>
                            <th class="p-4 text-yellow-300">Vente TTC</th>
                            <th class="p-4">Ratio</th>
                            <th class="p-4">Marge Brute</th>
                            <th class="p-4">Taux Marge %</th>
                        </tr>
                    </thead>
                    <tbody id="table-body" class="text-gray-700">
                        </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="text-center p-8 text-gray-400 text-xs">
        PassionFroid - Groupe Pomona &copy; 2026 | Document à usage interne restaurateurs.
    </footer>

    <script>
        // LES 12 PRODUITS DES FICHES (Données sources des captures)
        let products = [
            { name: "Entrecôte de bœuf régionale", buyKg: 17.99, weight: 220, sale: 25.00 },
            { name: "Onglet de bœuf à l'échalote", buyKg: 13.50, weight: 220, sale: 21.00 },
            { name: "Escalope de poulet à la crème", buyKg: 6.99, weight: 220, sale: 16.00 },
            { name: "Picanha de bœuf Angus Irlande", buyKg: 18.50, weight: 180, sale: 22.00 },
            { name: "Poire de bœuf Angus d'Irlande", buyKg: 18.50, weight: 200, sale: 20.50 },
            { name: "Suprême de pintade farcie", buyKg: 10.99, weight: 200, sale: 19.00 },
            { name: "Cordon bleu de porc pané", buyKg: 6.99, weight: 240, sale: 19.00 },
            { name: "Dos d'Eglefin sauce hollandaise", buyKg: 16.50, weight: 150, sale: 21.00 },
            { name: "Tartare de longe de thon", buyKg: 14.95, weight: 200, sale: 22.00 },
            { name: "Burger du chasseur", buyKg: 12.50, weight: 150, sale: 15.00 },
            { name: "Tartare de bœuf charolais", buyKg: 19.50, weight: 180, sale: 18.00 },
            { name: "Boudin noir aux oignons", buyKg: 7.45, weight: 250, sale: 18.00 }
        ];

        function renderTable() {
            const body = document.getElementById('table-body');
            body.innerHTML = '';

            products.forEach(p => {
                // FORMULES EXCEL REPRODUITES
                const parageCoef = 1.2; // Coefficient de perte (20%) constaté sur vos fiches
                const costPortionNet = (p.buyKg * parageCoef) * (p.weight / 1000);
                const garniture = costPortionNet * 0.30;
                const costAssietteTTC = (costPortionNet + garniture) * 1.055; // TVA 5.5%
                const ratio = p.sale / costAssietteTTC;
                const margeEuro = p.sale - costAssietteTTC;
                const margePct = (margeEuro / p.sale) * 100;

                const row = document.createElement('tr');
                row.className = "border-b hover:bg-purple-50 transition";
                row.innerHTML = `
                    <td class="p-4 font-semibold border-r">${p.name}</td>
                    <td class="p-4 text-center">${p.buyKg.toFixed(2)} €</td>
                    <td class="p-4 text-center text-gray-500">${p.weight}g</td>
                    <td class="p-4 text-center text-purple-pf font-medium">${costPortionNet.toFixed(2)} €</td>
                    <td class="p-4 text-center italic text-gray-500">${garniture.toFixed(2)} €</td>
                    <td class="p-4 text-center font-bold bg-gray-50">${costAssietteTTC.toFixed(2)} €</td>
                    <td class="p-4 text-center font-bold text-blue-700">${p.sale.toFixed(2)} €</td>
                    <td class="p-4 text-center text-gray-600">${ratio.toFixed(2)}</td>
                    <td class="p-4 text-center font-medium">${margeEuro.toFixed(2)} €</td>
                    <td class="p-4 text-center">
                        <span class="px-2 py-1 rounded text-white font-bold text-xs ${margePct > 75 ? 'bg-green-500' : (margePct > 70 ? 'bg-orange-500' : 'bg-red-500')}">
                            ${margePct.toFixed(2)} %
                        </span>
                    </td>
                `;
                body.appendChild(row);
            });
        }

        function addProduct() {
            const name = document.getElementById('new-name').value;
            const price = parseFloat(document.getElementById('new-price').value);
            const weight = parseInt(document.getElementById('new-weight').value);
            const sale = parseFloat(document.getElementById('new-sale').value);

            if(name && price && weight && sale) {
                products.unshift({ name, buyKg: price, weight, sale });
                renderTable();
                // Reset fields
                document.getElementById('new-name').value = '';
                document.getElementById('new-price').value = '';
                document.getElementById('new-weight').value = '';
                document.getElementById('new-sale').value = '';
            } else {
                alert("Veuillez remplir toutes les données.");
            }
        }

        // Initialisation
        renderTable();
    </script>
</body>
</html>