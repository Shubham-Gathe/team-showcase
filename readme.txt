=== Team Showcase ===
Contributors: Subham-Gathe
Tags: team, showcase, slider, slick, gallery
Requires at least: 5.0
Tested up to: 6.9
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A powerful and easy-to-use team showcase plugin for WordPress, built with modern PHP standards.

== Description ==

Team Showcase allows you to display your team members in a professional Slick Slider. Featuring a dynamic role filter, custom admin columns for easier management, and full REST API support for third-party integrations.

Key Features:
* **Slick Slider**: Smooth, responsive slider for team cards.
* **Role Filtering**: Dropdown filter to show/hide members by their designation.
* **Admin Settings**: Control slider settings like "Slides to Show" directly from the dashboard.
* **Custom Admin Columns**: View member photos and roles in the admin list view.
* **REST API Support**: Fetch team data via `/wp-json/2creative/v1/team-members`.
* **Security Hardened**: Built with strict escaping and sanitization standards.

== Installation ==

1. Upload the `team-showcase` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Team Showcase -> Add New Team Member to create your team.
4. Use the shortcode `[team_showcase]` on any page.

== API endpoints ==

GET /wp-json/2creative/v1/team-members
<==================expected output==========>
[
    {
        "id": 29,
        "name": "Shubham Gate",
        "role": "CA",
        "short_bio": "Self taught wp developer.",
        "photo_url": "http://motion-slider-plugin.local/wp-content/uploads/2026/01/woman-posing-forest-autumn-scaled.jpg",
        "permalink": "http://motion-slider-plugin.local/team-member/shubham-gate/",
        "social_links": {
            "facebook": "http://shubham.com",
            "twitter": "http://shubham.com",
            "linkedin": "http://shubham.com",
            "email": "shubham@gmail.com"
        }
    }
]
<=========================xxxxxxxxxx=======================>

GET /wp-json/2creative/v1/team-members/<id>
<==================expected output==========>
{
    "id": 29,
    "name": "Shubham Gate",
    "role": "CA",
    "short_bio": "Self taught wp developer.",
    "photo_url": "http://motion-slider-plugin.local/wp-content/uploads/2026/01/woman-posing-forest-autumn-scaled.jpg",
    "permalink": "http://motion-slider-plugin.local/team-member/shubham-gate/",
    "social_links": {
        "facebook": "http://shubham.com",
        "twitter": "http://shubham.com",
        "linkedin": "http://shubham.com",
        "email": "shubham@gmail.com"
    }
}
<=========================xxxxxxxxxx=======================>


== Frequently Asked Questions ==

= How do I change the number of slides? =
You can change the default in Settings, or use the shortcode attribute: `[team_showcase slides_to_show="4"]`.

= 1.0.0 =
* Initial release.
* I have used slick slider insted of grid layout as I find it better ux in case of dynamic number of team members.
* Custom admin columns (Photo & Role) this was mentioned optional.
* Added optional implementation of ajax based filtering using team member rest api.