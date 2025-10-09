<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PageContent;

class PageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing content
        PageContent::truncate();

        // Home page content - using the correct structure for the model
        $homeContent = [
            [
                'page' => 'home',
                'title' => 'Solidarité & Entraide',
                'content' => '<h2>À propos de nous</h2><p>L\'Association Westmount est une organisation d\'entraide et de solidarité qui accompagne ses membres dans les moments difficiles de la vie.</p><h2>Notre Mission</h2><p>Offrir un soutien financier et moral à nos membres en cas de décès d\'un proche, en créant un réseau d\'entraide basé sur la solidarité et la fraternité.</p><h2>Notre Vision</h2><p>Devenir la référence en matière d\'entraide communautaire, en créant un modèle de solidarité qui inspire d\'autres communautés.</p><h2>Nos Services</h2><p>Nous offrons une gamme complète de services pour soutenir nos membres</p><h3>Soutien Financier</h3><p>Aide financière immédiate en cas de décès d\'un membre de la famille</p><h3>Accompagnement Moral</h3><p>Soutien psychologique et accompagnement pendant les moments difficiles</p><h3>Réseau Communautaire</h3><p>Mise en relation avec d\'autres membres pour créer des liens durables</p><h2>Rejoignez notre communauté</h2><p>Faites partie d\'une communauté qui se soutient mutuellement dans les moments difficiles</p>',
                'meta_title' => 'Association Westmount - Solidarité & Entraide',
                'meta_description' => "L'Association Westmount Canada est une communauté solidaire et d'entraide qui vise à apporter un soutien à la famille d'un membre décédé. Ce soutien inclut notamment une aide financière pour aider la famille à faire face aux défis quotidiens.",
                'is_active' => true
            ]
        ];

        // About page content - using the correct structure for the model
        $aboutContent = [
            [
                'page' => 'about',
                'title' => 'À propos de l\'Association Westmount',
                'content' => '<h2>Notre Histoire</h2><p>L\'Association Westmount a été fondée en 2024 par un groupe de familles montréalaises qui souhaite promouvoir un système de solidarité pour optimiser les possibilités d\'entraide lors du départ d\'un proche parent.</p><h2>Notre Mission</h2><p>Offrir un soutien financier et moral à nos membres en cas de décès d\'un proche, en créant un réseau d\'entraide basé sur la solidarité et la fraternité.</p><h2>Notre Vision</h2><p>Être une référence en matière d\'associations d\'entraide au Canada, en développant un modèle durable et transparent.</p><h2>Nos Valeurs</h2><p>Solidarité, Transparence, Respect, Intégrité et Compassion guident nos actions.</p>',
                'meta_title' => 'À propos de l\'Association Westmount',
                'meta_description' => 'Découvrez l\'histoire, la mission et les valeurs de l\'Association Westmount, une organisation d\'entraide communautaire.',
                'is_active' => true
            ]
        ];

        // Contact page content - using the correct structure for the model
        $contactContent = [
            [
                'page' => 'contact',
                'title' => 'Contactez-nous',
                'content' => '<h2>Nous sommes là pour vous aider</h2><h3>Informations de contact</h3><p><strong>Téléphone:</strong> +1 (514) 555-0123</p><p><strong>Email:</strong> contact@associationwestmount.com</p><p><strong>Adresse:</strong> 123 Rue Westmount, Montréal, QC H3Z 1T1</p><p><strong>Heures d\'ouverture:</strong> Lun-Ven: 9h00-17h00</p><h3>Urgences</h3><p>Pour les urgences en dehors des heures d\'ouverture, contactez-nous par email et nous vous répondrons dans les plus brefs délais.</p>',
                'meta_title' => 'Contactez-nous - Association Westmount',
                'meta_description' => 'Contactez l\'Association Westmount pour toute question ou information. Nous sommes là pour vous aider.',
                'is_active' => true
            ]
        ];

        // Insert all content
        $allContent = array_merge($homeContent, $aboutContent, $contactContent);
        
        foreach ($allContent as $content) {
            PageContent::create($content);
        }
    }
}
