<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Plateforme de cours en ligne</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="/cours_en_ligne/cours_en_ligne/pages/etudiant/index.php" class="text-2xl font-bold">Youdemy</a>

            <!-- Liens principaux (centrés) -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/cours_en_ligne/cours_en_ligne/pages/etudiant/index.php" class="hover:text-gray-200">Accueil</a>
                <a href="/cours_en_ligne/cours_en_ligne/pages/etudiant/dashboard2.php" class="hover:text-gray-200">Cours</a>
                <a href="/cours_en_ligne/cours_en_ligne/pages/etudiant/categories.php" class="hover:text-gray-200">Catégories</a>
                <a href="/cours_en_ligne/cours_en_ligne/pages/etudiant/enseignants.php" class="hover:text-gray-200">Enseignants</a>
            </div>

            <!-- Liens de connexion/déconnexion (à droite) -->
            <div class="flex space-x-4">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="/cours_en_ligne/cours_en_ligne/pages/<?= $_SESSION['user']['role'] ?>/dashboard.php" class="hover:text-gray-200">Tableau de bord</a>
                    <a href="/cours_en_ligne/cours_en_ligne/includes/auth.php?action=logout" class="hover:text-gray-200">Déconnexion</a>
                <?php else: ?>
                    <a href="/cours_en_ligne/cours_en_ligne/auth/login.php" class="hover:text-gray-200">Connexion</a>
                    <a href="/cours_en_ligne/cours_en_ligne/auth/register.php" class="hover:text-gray-200">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
<main class="min-h-screen">
    <!-- Hero Section avec vidéo/image de fond -->
    <section class="relative min-h-screen flex items-center">
        <!-- Image de fond (utilisez une image de personnes étudiant dans un environnement moderne) -->
        <div class="absolute inset-0">
            <img src="../../images/scott-graham-5fNmWej4tAA-unsplash.jpg" alt="Students learning" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-black/70"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">Apprenez à votre rythme</h1>
                    <p class="text-xl mb-8 text-gray-200">Découvrez des milliers de cours en ligne dispensés par des experts de l'industrie.</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="catalogue.php" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold transition duration-300 inline-flex items-center">
                            <span>Commencer</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <a href="#courses" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition duration-300">
                            Voir les cours
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Section Avantages -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Avantage 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Apprentissage Flexible</h3>
                    <p class="text-gray-600">Étudiez à votre rythme, où que vous soyez. Accédez aux cours 24/7.</p>
                </div>

                <!-- Avantage 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Certifications Reconnues</h3>
                    <p class="text-gray-600">Obtenez des certificats validés par l'industrie pour booster votre carrière.</p>
                </div>

                <!-- Avantage 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Experts de l'Industrie</h3>
                    <p class="text-gray-600">Apprenez avec des professionnels reconnus dans leur domaine.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Catégories Populaires -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-16">Catégories Populaires</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <!-- Catégorie 1 - Développement Web -->
                <a href="#" class="group relative rounded-2xl overflow-hidden">
                    <img src="../../images/Capture d'écran 2025-01-19 002742.png" alt="Web Development" class="w-full h-48 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent group-hover:from-blue-900/80 transition duration-300"></div>
                    <div class="absolute bottom-0 left-0 p-6">
                        <h3 class="text-white font-bold mb-2">Développement Web</h3>
                        <p class="text-gray-200 text-sm">120+ cours</p>
                    </div>
                </a>

                <!-- Catégorie 2 - Design -->
                <a href="#" class="group relative rounded-2xl overflow-hidden">
                    <img src="../../images/Capture d'écran 2025-01-19 003132.png" alt="Design" class="w-full h-48 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent group-hover:from-blue-900/80 transition duration-300"></div>
                    <div class="absolute bottom-0 left-0 p-6">
                        <h3 class="text-white font-bold mb-2">Design & UX</h3>
                        <p class="text-gray-200 text-sm">85+ cours</p>
                    </div>
                </a>

                <!-- Catégorie 3 - Business -->
                <a href="#" class="group relative rounded-2xl overflow-hidden">
                    <img src="../../images/Capture d'écran 2025-01-19 003228.png" alt="Business" class="w-full h-48 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent group-hover:from-blue-900/80 transition duration-300"></div>
                    <div class="absolute bottom-0 left-0 p-6">
                        <h3 class="text-white font-bold mb-2">Business</h3>
                        <p class="text-gray-200 text-sm">150+ cours</p>
                    </div>
                </a>

                <!-- Catégorie 4 - Marketing -->
                <a href="#" class="group relative rounded-2xl overflow-hidden">
                    <img src="../../images/Capture d'écran 2025-01-19 003423.png" alt="Marketing" class="w-full h-48 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent group-hover:from-blue-900/80 transition duration-300"></div>
                    <div class="absolute bottom-0 left-0 p-6">
                        <h3 class="text-white font-bold mb-2">Marketing Digital</h3>
                        <p class="text-gray-200 text-sm">90+ cours</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Section Témoignages -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-16">Ce que disent nos étudiants</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Témoignage 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="flex items-center mb-6">
                        <img src="../../images/Capture d'écran 2025-01-19 002041.png" alt="Student" class="w-12 h-12 rounded-full">
                        <div class="ml-4">
                            <h4 class="font-bold">Sarah M.</h4>
                            <p class="text-gray-600">Développeuse Web</p>
                        </div>
                    </div>
                    <p class="text-gray-600">"Les cours sont très bien structurés et les instructeurs sont très compétents. J'ai pu trouver un emploi après avoir suivi la formation en développement web."</p>
                </div>

                <!-- Témoignage 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="flex items-center mb-6">
                        <img src="../../images/Capture d'écran 2025-01-19 002041.png" alt="Student" class="w-12 h-12 rounded-full">
                        <div class="ml-4">
                            <h4 class="font-bold">Mohammed K.</h4>
                            <p class="text-gray-600">Designer UX</p>
                        </div>
                    </div>
                    <p class="text-gray-600">"La qualité des cours et le support de la communauté sont exceptionnels. J'ai appris plus en 3 mois qu'en 1 an d'auto-formation."</p>
                </div>

                <!-- Témoignage 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="flex items-center mb-6">
                        <img src="../../images/Capture d'écran 2025-01-19 002041.png" alt="Student" class="w-12 h-12 rounded-full">
                        <div class="ml-4">
                            <h4 class="font-bold">Leila B.</h4>
                            <p class="text-gray-600">Marketing Manager</p>
                        </div>
                    </div>
                    <p class="text-gray-600">"Une plateforme incroyable pour développer ses compétences. Les cours sont pratiques et directement applicables dans le monde professionnel."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="relative py-20 overflow-hidden">
        <div class="absolute inset-0">
            <img src="../../images/Capture d'écran 2025-01-19 003648.png" alt="CTA Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-blue-900/90"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Prêt à transformer votre carrière ?</h2>
            <p class="text-xl text-gray-200 mb-8">Rejoignez des milliers d'étudiants et commencez votre voyage d'apprentissage dès aujourd'hui.</p>
            <a href="catalogue.php" class="bg-white text-blue-900 px-8 py-4 rounded-lg font-semibold hover:bg-blue-100 transition duration-300 inline-flex items-center">
                <span>Commencer maintenant</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </a>
        </div>
    </section>

    
</main>

<?php include '../../includes/footer.php'; ?>