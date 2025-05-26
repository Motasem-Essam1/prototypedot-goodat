$(document).ready(function () {

  // International Telephone Validation
  $(function () {

    // Enter Numbers Only
    $(function(){
      $("#phoneNumber, .only-numbers").keypress(function(e){
        // allowed char: 1 , 2 , 3, 4, 5, N, O, A, B, C
        let allow_char = [8, 9, 13, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57];
        if(allow_char.indexOf(e.which) !== -1 ){
          return true;
        }
        else{
          return false;
        }
      });
    });

    // var input = document.querySelector("#phoneNumber");
    //   window.intlTelInput(input, {
    //     separateDialCode: true,
    //     initialCountry: "ie",
    //     utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.0.3/js/utils.js",
    //       customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
    //         document.getElementById("phone_code").setAttribute("value", selectedCountryData.dialCode);
    //         return selectedCountryPlaceholder;
    //       },
    //   });
    //
    // function changeCountryFlag() {
    //   data.forEach((el, i) => {
    //     $(`.iti__flag.iti__${el.code}`).attr('style', `background-image: url(assets/img/flags/${el.code}.svg) !important; background-position: center !important;
    //     background-size: cover !important;`);
    //   });
    // }
    //
    // changeCountryFlag();
    //
    // window.onload = function(e){
    //   changeCountryFlag();
    // }
    //
    // $(document).on('click', ".iti__country", function () {
    //   // var dial = $(this).attr('data-dial-code');
    //   var key = $(this).attr('data-country-code');
    //   // $(".iti__selected-dial-code").text('+' + dial);
    //   $(".iti__selected-flag .iti__flag").attr('style', `background-image: url(assets/img/flags/${key}.svg) !important; background-position: center !important;background-size: cover !important;`);
    // });
  });

  // Country Code config
  $(function () {
      let dataCountries = [];
      countriesDialCode.forEach((row) => {
          const isoToLower = row.iso.toLowerCase();
          dataCountries.push({
              // html: `<img class="img-fluid" src="../img/flags/${isoToLower}.svg" width="20" /> +${row.code}`,
              // html: `<img class="img-fluid" src="https://goodat.prototypedot-eg.com/uploads/user-profile/2-1688395036.jpeg" width="20" /> +${row.code}`,
              value: row.code,
              text: `<span class="flag-icon-parent"><img class="img-fluid flag-icon" src="assets/img/flags/${isoToLower}.svg" /></span>
                    <span class="country-name">${row.country}</span>
                    <span class="dial-code-placeholder">(+${row.code})</span>`,
              selected: isoToLower == 'ie'
          });
      });

      var displaySelect = new SlimSelect({
          select: '#countryCode',
          data: dataCountries,
      });

      let val = $("#countryCode").data('value');
      if (val) {
          displaySelect.setSelected(val);
      }

  });

  // Tabs Inside Tabs
  $(function () {
    $(function () {
      $(".tt-btn").on('click', function () {
        var id = $(this).attr("id");
        $(this).addClass('active').siblings().removeClass("active");
        $(`div[data-tab-target="#${id}"]`).addClass("active").siblings().removeClass("active");
        // $(`div[data-tab-target="#${id}"]`)
      });
    });

    $(function () {
      $(".stc-btn").on('click', function () {
        var id = $(this).attr("id");
        $(this).addClass('active').siblings().removeClass("active");
        // $(`div[data-tab-target="#${id}"]`).addClass("active").siblings().removeClass("active");
      });
    });
  });

  // Show (+ , -) in Accordion
  $(function () {
    // Add minus icon for collapse element which is open by default
    $(".collapse.show").each(function () {
      $(this)
        .parent()
        .find(".icon-minus")
        .removeClass("icon-minus")
        .addClass("icon-plus");
    });

    // Toggle plus minus icon on show hide of collapse element
    $(".collapse")
      .on("show.bs.collapse", function () {
        $(this)
          .parent()
          .find(".icon-plus")
          .removeClass("icon-plus")
          .addClass("icon-minus");
      })
      .on("hide.bs.collapse", function () {
        $(this)
          .parent()
          .find(".icon-minus")
          .removeClass("icon-minus")
          .addClass("icon-plus");
      });
  });

  // Range Slider Search
  $(function () {
    $(".js-range-slider-price").ionRangeSlider({
      onStart: function (data) {
        $("#mfgdInputValPrice-1").text(data.from);
        $("#mfgdInputValPrice-2").text(data.to);
        $("#price-start-values").val(data.from);
        $("#price-end-values").val(data.to);
      },
      onChange: function (data) {
        $("#mfgdInputValPrice-1").text(data.from);
        $("#mfgdInputValPrice-2").text(data.to);
        $("#price-start-values").val(data.from);
        $("#price-end-values").val(data.to);
      },
      onUpdate: function (data) {
        $("#mfgdInputValPrice-1").text(data.from);
        $("#mfgdInputValPrice-2").text(data.to);
        $("#price-start-values").val(data.from);
        $("#price-end-values").val(data.to);
      },
    });

  });

  // Read More
  $(function () {
    $(".read-more-btn").on("click", function () {
      var content = $(this)
        .parents(".read-more-parent")
        .find(".read-more-content");
      content.text(content.attr("data-text"));
      $(this).css("display", "none");
    });

    $(".read-more-content").each(function () {
      var length = $(this).attr("data-length");
      var len = $(this).text().length;
      if (length > len) {
        $(this).next('.read-more-btn').css("display", "none");
      }
      var strToInt = parseInt(length);
      $(this).attr("data-text", $(this).text());
      $(this).text($(this).text().substr(0, strToInt));
    });
  });

  // Calculate Cards
  $(function () {
    // The number of comments you want to show in start
    var counter = 0;
    if ($(window).width() < 576) {
      counter = 5;
    } else {
      counter = 5;
    }

    // The number of comments you want to generate
    $("#viewMoreBtn").on("click", function () {

      if ($(window).width() < 576) {
        counter = counter + 1;
      } else {
        counter += 1;
      }
      generateMore(counter);
    });

    function generateMore(counter) {
      $(`.vpr-comment-row`).attr("style", "display: none");
      for (let x = 0; x <= counter; x++) {
        $(`.vpr-comment-row:nth-of-type(${x})`).attr(
          "style",
          "display: block"
        );
      }
      checkElementsShowen();
    }
    generateMore(counter);

    function checkElementsShowen() {
      if ($(".vpr-comment-row").last().css('display') == 'block') {
        $("#viewMoreBtn").css('display', ' none');
      }
    }
  });

  // All Countries
  $(function () {
    var $select = $('#country-selectize').selectize({
      maxItems: 1,
      labelField: 'name',
      valueField: 'code',
      searchField: false, // ['name', 'code']
      options: data,
      preload: true,
      persist: false,
      render: {
          item: function(item, escape) {
              return "<div><img class='img-selected-flag' src='assets/img/flags/" + escape(item.code) + ".svg' /> &nbsp;" + escape(item.name) + "</div>";
          },
          option: function(item, escape) {
            return "<div><img class='img-option-flag' src='assets/img/flags/" + escape(item.code) + ".svg' /> &nbsp;" + escape(item.name) + "</div>";
          }
      },
    });
    $select[0].selectize.setValue("ie");

  });

  // Upload And Close Image View
  $(function () {
    // View Image
    $('.input-file-reader').on('change', function (event) {
      var output = $(this).parents('.inputfr-parent').find('.img-file-reader');
      var showImage = $(this).parents('.inputfr-parent').find('.imgfr-parent');
      output.attr('src', URL.createObjectURL(event.target.files[0]));
      output.onload = function() {
        URL.revokeObjectURL(output.attr('src')); // free memory
      }
      showImage.addClass('active');
    });

    // Remove Fake Image View
    $('.close-img-reader').on('click', function (event) {
      var input = $(this).parents('.inputfr-parent').find('.input-file-reader');
      $(this).parents('.imgfr-parent').removeClass('active');
      $(this).parents('.imgfr-parent').find('.img-file-reader').attr('src', '');
      input.val(null);
    });

    // Remove Image From Database
    $('.delete-img-btn').on('click', function (e) {
      let imageId = parseInt($(this).data('image-id'));
      let imageType = $(this).data('image-type');
      let itemId = parseInt($(this).data('item-id'));
      let thisItem = $(this);
      let url = "";
      let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var input = $(this).parents('.inputfr-parent').find('.input-file-reader');
      input.val(null);
        // $(this).parents('.imgfr-parent').removeClass('active');
        // $(this).parents('.imgfr-parent').find('.img-file-reader').attr('src', '');
        // $(this).parents('.imgfr-parent').find('.close-img-reader').removeClass('unactive');
        // $(this).remove();

        if (imageType == "service") {
            url = "account/delete-service-images";
        } else if (imageType == "task") {
            url = "account/delete-task-images";
        }

        $.ajax({
            url,
            type: "POST",
            data: {
                _token: CSRF_TOKEN,
                images_id: imageId,
                [imageType + '_id']: itemId,
            },
            beforeSend: function() {
                $(`#loader-${imageId}`).addClass('active');
            },
            success: function (data) {
                $(`#loader-${imageId}`).removeClass('active');
                thisItem.parents('.imgfr-parent').removeClass('active');
                thisItem.parents('.imgfr-parent').find('.img-file-reader').attr('src', '');
                thisItem.parents('.imgfr-parent').find('.close-img-reader').removeClass('unactive');
                thisItem.remove();
            },
            error: function(data) {
                $(`#loader-${imageId}`).removeClass('active');
                thisItem.parents('.imgfr-parent').removeClass('active');
                thisItem.parents('.imgfr-parent').find('.img-file-reader').attr('src', '');
                thisItem.parents('.imgfr-parent').find('.close-img-reader').removeClass('unactive');
                thisItem.remove();
            },
        });



    });
  });

    // Copy
    $("#copyClipboard").on('click', function () {
        var copyText = $(this).data('text');
        navigator.clipboard
            .writeText(copyText)
            .then(() => {
                var notyf = new Notyf(
                    {
                        position: {
                            x: 'right',
                            y: 'top',
                        }
                    }
                );
                notyf.success(`Your invitation code copied successfully! (${copyText})`);
            })
            .catch(() => {
                alert("something went wrong");
            });
    });

    // Share Social Media
    // Copy
    $("#copyUrlBrowser").on('click', function () {
        var copyText = document.location.href;
        navigator.clipboard
            .writeText(copyText)
            .then(() => {
                // alert("successfully copied");
                $('#copyUrlBrowser .url-text').text('Copied!')
                setTimeout(() => {
                    $('#copyUrlBrowser .url-text').text('Copy Link')
                }, [800])
            })
            .catch(() => {
                alert("something went wrong");
            });
    });

    // Share Facebook Link
    $("#shareFacebook").on('click', function () {
        var url = window.location.href;
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
    });

    window.fbAsyncInit = function() {
        FB.init({
            appId: '629322731899614',
            xfbml: true,
            version: 'v2.9'
        });
        FB.AppEvents.logPageView();
    };
    function isMobile() {
        const toMatch = [/Android/i, /webOS/i, /iPhone/i, /iPad/i, /iPod/i, /BlackBerry/i, /Windows Phone/i];
        return toMatch.some((toMatchItem) => {
            return navigator.userAgent.match(toMatchItem);
        });
    }

    $("#shareMessenger").on('click', function () {
        var url = window.location.href;
        if (isMobile()) {
            window.location.href = "fb-messenger://share/?link=" + url;
        } else {
            FB.ui({
                method: 'send',
                link: url,
                title: 'this is test',
                redirect_uri: url
            });
        }
    });

    $(".wap-btn").on('click', function () {
        var url = window.location.href;
        var name = $(this).data('name');
        var phone = $(this).data('phone');
        var text = `https://wa.me/${phone}?&text=${url}`;
        // var text = 'https://wa.me/201222212766?&text=' + url + ' ' + title;
        window.open(text,'_blank');
    });

    $(function () {
        // Search
        $("#searchInput, #pac-input").on('keyup', function () {
            let searchGroup = $("#searchGroup");
            let search = $(this);
            let dropdown = $("#searchDropdown");
            let cancelSearch = $("#cancelSearch");
            if (search.val() != '') {
                dropdown.addClass('active');
                searchGroup.addClass('active');
                cancelSearch.addClass('active');
            } else {
                dropdown.removeClass('active');
                searchGroup.removeClass('active');
                cancelSearch.removeClass('active');
            }
        });

        function resetDropdownSearch(type) {
            let searchGroup = $("#searchGroup");
            let searchInput = $("#searchInput, #pac-input");
            let dropdown = $("#searchDropdown");
            let cancelSearch = $("#cancelSearch");
            dropdown.removeClass('active');
            searchGroup.removeClass('active');
            cancelSearch.removeClass('active');
            if (type == 'btn-cancel') {
                searchInput.val('');
            }
            $(".search-dropdown-loading").removeClass("active");
        }

        // Close search
        $(document).on("click", function () {
            resetDropdownSearch('document');
        });

        $("#cancelSearch").on("click", function (e) {
            resetDropdownSearch('btn-cancel');
        });

        // $(document).on('click', '.close-dd-btn', function () {
        //     resetDropdownSearch('btn-cancel');
        //     console.log('clicked');
        // });

        $("#searchDropdown, #searchGroup").click(function(e) {
            const close = $(this).hasClass('access-close');
            if (e.target.parentNode.id && e.target.parentNode.id == 'cancelSearch') {
                resetDropdownSearch();
            } else {
                if (!close) {
                    e.stopPropagation();
                } else {
                    return true;
                }
            }
        });
    });

    // Notifications
    $(function () {
        $(".notifications-btn").on('click', function (e) {
            e.stopPropagation();
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $("#notificationsDropdown").removeClass('active');
            } else {
                $(this).addClass('active');
                $("#notificationsDropdown").addClass('active');
            }
        });

        $(document).on("click", function () {
            if ($("#notificationsDropdown").hasClass('active')) {
                $('.notifications-btn').removeClass('active');
                $("#notificationsDropdown").removeClass('active');
            }
        });

        $("#notificationsDropdown").on("click", function (e) {
            e.stopPropagation();
        });
    });

    // Rating
    $("#starRating").on('change', function (e) {
        if (e.target.value) {
            $('#ratingModal').modal('show')
        }
    });

    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on("click", ".favorite-btn", function () {
            let favoriteId = $(this).data('id');
            let type = $(this).data('type');
            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/toggle-favorite',
                type: "POST",
                data: {_token: CSRF_TOKEN, favorite_id: favoriteId, item_type: type},
                beforeSend: function() {
                    // Service
                    if (type == 'service') {
                        $(`#service_fav_loading_${favoriteId}`).addClass('active');
                        $(`#service_fav_content_${favoriteId}`).addClass('active');
                        $(`#service_fav_btn_${favoriteId}`).attr('disabled', true);
                    }

                    // Task
                    if (type == 'task') {
                        $(`#task_fav_loading_${favoriteId}`).addClass('active');
                        $(`#task_fav_content_${favoriteId}`).addClass('active');
                        $(`#task_fav_btn_${favoriteId}`).attr('disabled', true);
                    }

                    // Provider
                    if (type == 'provider') {
                        $(`#provider_fav_loading_${favoriteId}`).addClass('active');
                        $(`#provider_fav_content_${favoriteId}`).addClass('active');
                        $(`#provider_fav_btn_${favoriteId}`).attr('disabled', true);
                    }
                },
                success: function (data) {
                    var notyf = new Notyf(
                        {
                            position: {
                                x: 'right',
                                y: 'top',
                            }
                        }
                    );
                    // Service
                    if (type == 'service') {
                        $(`#service_fav_loading_${favoriteId}`).removeClass('active');
                        $(`#service_fav_content_${favoriteId}`).removeClass('active');
                        $(`#service_fav_btn_${favoriteId}`).attr('disabled', false);
                        $(`#service_fav_count_${favoriteId}`).text(data.data.item.likes_count);
                        if (data.success && data.liked) {
                            $(`#service_fav_btn_${data.data.favorite_id}`).addClass('active');
                            notyf.success(`${data.message}`);
                        }
                        if (data.success && data.unliked) {
                            $(`#service_fav_btn_${data.data.favorite_id}`).removeClass('active');
                            notyf.error(`${data.message}`);
                        }
                    }

                    // Task
                    if (type == 'task') {
                        $(`#task_fav_loading_${favoriteId}`).removeClass('active');
                        $(`#task_fav_content_${favoriteId}`).removeClass('active');
                        $(`#task_fav_btn_${favoriteId}`).attr('disabled', false);
                        $(`#task_fav_count_${favoriteId}`).text(data.data.item.likes_count);
                        if (data.success && data.liked) {
                            $(`#task_fav_btn_${data.data.favorite_id}`).addClass('active');
                            notyf.success(`${data.message}`);
                        }
                        if (data.success && data.unliked) {
                            $(`#task_fav_btn_${data.data.favorite_id}`).removeClass('active');
                            notyf.error(`${data.message}`);
                        }
                    }

                    // Provider
                    if (type == 'provider') {
                        $(`#provider_fav_loading_${favoriteId}`).removeClass('active');
                        $(`#provider_fav_content_${favoriteId}`).removeClass('active');
                        $(`#provider_fav_btn_${favoriteId}`).attr('disabled', false);
                        $(`#provider_fav_count_${favoriteId}`).text(data.data.item.likes_count);
                        if (data.success && data.liked) {
                            $(`#provider_fav_btn_${data.data.favorite_id}`).addClass('active');
                            notyf.success(`${data.message}`);
                        }
                        if (data.success && data.unliked) {
                            $(`#provider_fav_btn_${data.data.favorite_id}`).removeClass('active');
                            notyf.error(`${data.message}`);
                        }
                    }

                },
                error: function(data) {
                    // Service
                    if (type == 'service') {
                        $(`#service_fav_loading_${favoriteId}`).removeClass('active');
                        $(`#service_fav_content_${favoriteId}`).removeClass('active');
                    }

                    // Task
                    if (type == 'task') {
                        $(`#task_fav_loading_${favoriteId}`).removeClass('active');
                        $(`#task_fav_content_${favoriteId}`).removeClass('active');
                    }

                    // Provider
                    if (type == 'provider') {
                        $(`#provider_fav_loading_${favoriteId}`).removeClass('active');
                        $(`#provider_fav_content_${favoriteId}`).removeClass('active');
                    }
                },
            });
        });
    });

    // Tooltip
    $("body").tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    // Toggle Password
    $(function () {
        $(".password-display-btn").on('click', function () {
            const icon = $(this).find('.password-eye');
            const input = $(this).parents('.password-display').find('input.fgr-input');
            const inputType = input.attr('type');
            if (inputType == 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye-slash');
                icon.addClass('fa-eye');
            } else {
                input.attr('type', 'password');
                icon.addClass('fa-eye-slash');
                icon.removeClass('fa-eye');
            }
        });
    });

    // Provider contact phone number
    $(function () {
        $('.add-provider-contact').on('click', function (e) {
            e.preventDefault();
            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let userId      = $(this).data('user-id');
            let itemId      = $(this).data('item-id');
            let itemType    = $(this).data('item-type');
            let modal       = $(this).data('modal');
            let phone       = $(this).data('phone');
            $.ajax({
                url: "/provider-contact",
                type: "POST",
                data: {
                    _token: CSRF_TOKEN,
                    user_id: userId,
                    item_id: itemId,
                    item_type: itemType
                },
                beforeSend: function() {},
                success: function (data) {
                    if (modal) {
                        $("#upgradeMessageDialog").modal('show');
                    } else {
                        window.alert(`The phone Number Is: ${phone}`);
                        window.location.href = `tel:${phone}`;
                    }
                },
                error: function(data) {},
            });
        });
    });

    // Form Validation
    $("#formLoginDialog").validate({
        rules: {
            phone_number: {
                required: true,
                minlength: 7
            },
            password: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            phone_number: {
                required: "Please enter your phone number",
                minlength: "Your phone number must be at least 7 numbers"
            },
            password: {
                required: "Please enter a password",
                minlength: "Your password must be at least 6 characters"
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

});

// Calculate Distance
$(document).ready(function () {
    function currentLocation() {
        navigator.geolocation.getCurrentPosition(position => {
                const location = position.coords;
                renderKilometers(location.latitude, location.longitude);
            }, () => {
                // loading: false
            }
        );
    }
    currentLocation();

    function renderKilometers(currentLat, currentLng) {
        $('.kilometers-distance').each(function(idx, element) {
            let lat = $(this).data('lat');
            let lng = $(this).data('lng');
            let selector = $(this).data('selector');
            console.log(lat, lng, currentLat, currentLng, 'K', element.id);
            console.log(element.id);
            distance(lat, lng, currentLat, currentLng, 'K', element.id);
        });
    }

    function distance(lat1, lon1, lat2, lon2, unit, selector) {
        if ((lat1 == lat2) && (lon1 == lon2)) {
            $('#' + selector).text('0 KM ');
        }
        else {
            var radlat1 = Math.PI * lat1/180;
            var radlat2 = Math.PI * lat2/180;
            var theta = lon1-lon2;
            var radtheta = Math.PI * theta/180;
            var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
            if (dist > 1) {
                dist = 1;
            }
            dist = Math.acos(dist);
            dist = dist * 180/Math.PI;
            dist = dist * 60 * 1.1515;
            if (unit == "K") { dist = dist * 1.609344 };
            if (unit == "N") { dist = dist * 0.8684 };
            console.log(dist.toFixed(2) + ' KM ');
            $('#' + selector).text(dist.toFixed(2) + ' KM ');
        }
    }
});

// const formatCash = n => {
//     if (n < 1e3) return n;
//     if (n >= 1e3 && n < 1e6) return +(n / 1e3).toFixed(1) + "K";
//     if (n >= 1e6 && n < 1e9) return +(n / 1e6).toFixed(1) + "M";
//     if (n >= 1e9 && n < 1e12) return +(n / 1e9).toFixed(1) + "B";
//     if (n >= 1e12) return +(n / 1e12).toFixed(1) + "T";
// };
//
// console.log(formatCash(1235000));
