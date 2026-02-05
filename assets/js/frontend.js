(function ($) {
    'use strict';

    $(document).ready(function () {
        var $slider = $('.team-slick-slider');
        var $select = $('.team-role-select');

        function getSliderSettings() {
            var slidesToShow = parseInt($slider.data('slides-to-show')) || 3;
            return {
                slidesToShow: slidesToShow,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                dots: true,
                arrows: true,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: Math.min(slidesToShow, 2),
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            };
        }

        function initSlider() {
            if ($slider.length && $slider.find('.team-slider-item').length > 0) {
                $slider.slick(getSliderSettings());
            }
        }

        function renderMember(member) {
            var primaryColor = (typeof teamShowcaseSettings !== 'undefined' && teamShowcaseSettings.primary_color) ? teamShowcaseSettings.primary_color : '#3498db';
            var html = '<div class="team-slider-item" data-role="' + member.role + '">';
            html += '<div class="team-member-card" id="team-member-' + member.id + '">';

            // Image
            html += '<div class="team-member-image">';
            if (member.photo_url) {
                html += '<img src="' + member.photo_url + '" alt="' + member.name + '" class="attachment-medium size-medium">';
            } else {
                html += '<div class="team-member-placeholder"></div>';
            }
            html += '</div>';

            // Info
            html += '<div class="team-member-info">';
            html += '<h3 class="team-member-name"><a href="' + member.permalink + '">' + member.name + '</a></h3>';
            if (member.role) {
                html += '<p class="team-member-designation" style="color: ' + primaryColor + ';">' + member.role + '</p>';
            }

            // Social
            html += '<div class="team-member-social">';
            if (member.social_links) {
                if (member.social_links.facebook) {
                    html += '<a href="' + member.social_links.facebook + '" target="_blank" class="social-icon facebook" title="Facebook"><span class="dashicons dashicons-facebook"></span></a>';
                }
                if (member.social_links.twitter) {
                    html += '<a href="' + member.social_links.twitter + '" target="_blank" class="social-icon twitter" title="Twitter"><span class="dashicons dashicons-twitter-alt"></span></a>';
                }
                if (member.social_links.linkedin) {
                    html += '<a href="' + member.social_links.linkedin + '" target="_blank" class="social-icon linkedin" title="LinkedIn"><span class="dashicons dashicons-linkedin"></span></a>';
                }
                if (member.social_links.email) {
                    html += '<a href="mailto:' + member.social_links.email + '" class="social-icon email" title="Email"><span class="dashicons dashicons-email"></span></a>';
                }
            }
            html += '</div>'; // End social
            html += '</div>'; // End info
            html += '</div>'; // End card
            html += '</div>'; // End slider item

            return html;
        }

        // Initial load
        initSlider();

        // AJAX Filter Logic
        $select.on('change', function () {
            var selectedRole = $(this).val();
            var apiUrl = (typeof teamShowcaseSettings !== 'undefined') ? teamShowcaseSettings.rest_url + '/team-members' : '/wp-json/2creative/v1/team-members';

            if (selectedRole !== 'all') {
                apiUrl += (apiUrl.indexOf('?') !== -1 ? '&' : '?') + 'role=' + encodeURIComponent(selectedRole);
            }

            // Loading state
            $slider.css('opacity', '0.5');

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    // Destroy existing slick if initialized
                    if ($slider.hasClass('slick-initialized')) {
                        $slider.slick('unslick');
                    }

                    // Clear slider
                    $slider.empty();

                    if (data && data.length > 0) {
                        var itemsHtml = '';
                        data.forEach(function (member) {
                            itemsHtml += renderMember(member);
                        });
                        $slider.html(itemsHtml);

                        // Re-init slider
                        initSlider();
                    } else {
                        $slider.html('<p class="team-no-results">No team members found.</p>');
                    }

                    $slider.css('opacity', '1');
                })
                .catch(error => {
                    console.error('Error fetching team members:', error);
                    $slider.css('opacity', '1');
                });
        });
    });

})(jQuery);
