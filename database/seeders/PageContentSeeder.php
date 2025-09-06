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

        // Home page content - matching the actual home page structure
        $homeContent = [
            // Hero section
            ['page' => 'home', 'section' => 'hero', 'key' => 'title', 'value' => 'Solidarité & Entraide', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'home', 'section' => 'hero', 'key' => 'description', 'value' => 'Rejoignez une communauté qui se soutient mutuellement dans les moments difficiles. Ensemble, nous sommes plus forts.', 'type' => 'text', 'sort_order' => 2],
            
            // About section
            ['page' => 'home', 'section' => 'about', 'key' => 'title', 'value' => 'À propos de nous', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'home', 'section' => 'about', 'key' => 'description', 'value' => 'L\'Association Westmount est une organisation d\'entraide et de solidarité qui accompagne ses membres dans les moments difficiles de la vie.', 'type' => 'text', 'sort_order' => 2],
            
            // Mission section
            ['page' => 'home', 'section' => 'mission', 'key' => 'title', 'value' => 'Notre Mission', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'home', 'section' => 'mission', 'key' => 'content', 'value' => 'Offrir un soutien financier et moral à nos membres en cas de décès d\'un proche, en créant un réseau d\'entraide basé sur la solidarité et la fraternité.', 'type' => 'text', 'sort_order' => 2],
            
            // Vision section
            ['page' => 'home', 'section' => 'vision', 'key' => 'title', 'value' => 'Notre Vision', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'home', 'section' => 'vision', 'key' => 'content', 'value' => 'Devenir la référence en matière d\'entraide communautaire, en créant un modèle de solidarité qui inspire d\'autres communautés.', 'type' => 'text', 'sort_order' => 2],
            
            // Services section
            ['page' => 'home', 'section' => 'services', 'key' => 'title', 'value' => 'Nos Services', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'home', 'section' => 'services', 'key' => 'description', 'value' => 'Nous offrons une gamme complète de services pour soutenir nos membres', 'type' => 'text', 'sort_order' => 2],
            
            // Service 1
            ['page' => 'home', 'section' => 'service1', 'key' => 'title', 'value' => 'Soutien Financier', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'home', 'section' => 'service1', 'key' => 'description', 'value' => 'Aide financière immédiate en cas de décès d\'un membre de la famille', 'type' => 'text', 'sort_order' => 2],
            
            // Service 2
            ['page' => 'home', 'section' => 'service2', 'key' => 'title', 'value' => 'Accompagnement Moral', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'home', 'section' => 'service2', 'key' => 'description', 'value' => 'Soutien psychologique et accompagnement pendant les moments difficiles', 'type' => 'text', 'sort_order' => 2],
            
            // Service 3
            ['page' => 'home', 'section' => 'service3', 'key' => 'title', 'value' => 'Réseau Communautaire', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'home', 'section' => 'service3', 'key' => 'description', 'value' => 'Mise en relation avec d\'autres membres pour créer des liens durables', 'type' => 'text', 'sort_order' => 2],
            
            // CTA section
            ['page' => 'home', 'section' => 'cta', 'key' => 'title', 'value' => 'Rejoignez notre communauté', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'home', 'section' => 'cta', 'key' => 'description', 'value' => 'Faites partie d\'une communauté qui se soutient mutuellement dans les moments difficiles', 'type' => 'text', 'sort_order' => 2],
        ];

        // About page content - matching the actual about page structure
        $aboutContent = [
            ['page' => 'about', 'section' => 'hero', 'key' => 'title', 'value' => 'À propos de l\'Association Westmount', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'about', 'section' => 'hero', 'key' => 'subtitle', 'value' => 'Notre histoire, notre mission, notre vision', 'type' => 'text', 'sort_order' => 2],
            
            ['page' => 'about', 'section' => 'history', 'key' => 'title', 'value' => 'Notre Histoire', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'about', 'section' => 'history', 'key' => 'content', 'value' => 'Fondée en 1998, l\'Association Westmount est née de la volonté de créer un réseau d\'entraide solide au sein de notre communauté. Depuis plus de 25 ans, nous avons aidé des centaines de familles à traverser les moments difficiles.', 'type' => 'text', 'sort_order' => 2],
            
            ['page' => 'about', 'section' => 'mission', 'key' => 'title', 'value' => 'Notre Mission', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'about', 'section' => 'mission', 'key' => 'content', 'value' => 'Offrir un soutien financier et moral à nos membres en cas de décès d\'un proche, en créant un réseau d\'entraide basé sur la solidarité et la fraternité.', 'type' => 'text', 'sort_order' => 2],
            
            ['page' => 'about', 'section' => 'vision', 'key' => 'title', 'value' => 'Notre Vision', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'about', 'section' => 'vision', 'key' => 'content', 'value' => 'Devenir la référence en matière d\'entraide communautaire, en créant un modèle de solidarité qui inspire d\'autres communautés.', 'type' => 'text', 'sort_order' => 2],
            
            ['page' => 'about', 'section' => 'values', 'key' => 'title', 'value' => 'Nos Valeurs', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'about', 'section' => 'values', 'key' => 'content', 'value' => 'Solidarité, Fraternité, Transparence, Respect, et Entraide sont les valeurs qui nous guident dans toutes nos actions.', 'type' => 'text', 'sort_order' => 2],
        ];

        // Contact page content - matching the actual contact page structure
        $contactContent = [
            ['page' => 'contact', 'section' => 'hero', 'key' => 'title', 'value' => 'Contactez-nous', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'contact', 'section' => 'hero', 'key' => 'subtitle', 'value' => 'Nous sommes là pour vous aider', 'type' => 'text', 'sort_order' => 2],
            
            ['page' => 'contact', 'section' => 'info', 'key' => 'phone', 'value' => '+1 (514) 555-0123', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'contact', 'section' => 'info', 'key' => 'email', 'value' => 'contact@associationwestmount.com', 'type' => 'text', 'sort_order' => 2],
            ['page' => 'contact', 'section' => 'info', 'key' => 'address', 'value' => '123 Rue Westmount, Montréal, QC H3Z 1T1', 'type' => 'text', 'sort_order' => 3],
            ['page' => 'contact', 'section' => 'info', 'key' => 'hours', 'value' => 'Lun-Ven: 9h00-17h00', 'type' => 'text', 'sort_order' => 4],
            
            ['page' => 'contact', 'section' => 'emergency', 'key' => 'title', 'value' => 'Urgences', 'type' => 'text', 'sort_order' => 1],
            ['page' => 'contact', 'section' => 'emergency', 'key' => 'description', 'value' => 'Pour les urgences en dehors des heures d\'ouverture, contactez-nous par email et nous vous répondrons dans les plus brefs délais.', 'type' => 'text', 'sort_order' => 2],
        ];

        // Insert all content
        $allContent = array_merge($homeContent, $aboutContent, $contactContent);
        
        foreach ($allContent as $content) {
            PageContent::create($content);
        }
    }
}
