
<script type="text/javascript">
    // Shorthand for $( document ).ready()
    $(function() {
        /* DEMO */
        // initialize variables
        var start = 2;
        var initialPosts = {{$getPosts}};
        var desiredPosts = 2
        // either null or contains the mustache template
        var template = '<div class="item">'
                +'<img class="thumbnail pull-left" width="50px" height="50px" src="http://placehold.it/50x50" style="margin: 0px 10px 5px 0px;">'
                +'<a href="#"><h4 style="margin: 5px 0px;" class="post-title"></h4></a>'
                +'<p><small class="post-content"></small></p>'
                +'<div class="well well-small post-date"></div>'
                +'</div>';

        var widget = $('#widget'),
        // Element to load the posts
                content = widget.find('.content'),
        // the more button
                more = widget.find('.more'),
        // the post counter
                counter = widget.find('.badge');

        // Create alerts elements (Display Success or Failure)
        var alerts = {
            requestEmpty : $('<div class="alert alert-info">No more data</div>'),
            requestFailure : $('<div class="alert alert-error">Could not get the data. Try again!</div>')
        }
        var progressElement = $('<div class="progress" style="margin-bottom:0"><div class="bar"></div></div>');
        var progressBar = progressElement.find('.bar');

        // function that handle posts
        var postHandler = function(posts){
            // Set the progress bar to 100%
            progressBar.css('width', '100%');
            // Delay the normal more button to come back for a better effect
            window.setTimeout(function(){more.html('More <span class="caret"></span>')}, 500);
            // insert childrens at the end of the content element
            for (post in posts){
                // Clone the element
                var $post = $(template).clone();
                $post.attr('id', 'post-' + posts[post].ID);
                $post.find('.post-title').html(posts[post].post_title);
                $post.find('.post-content').html(posts[post].post_content);
                $post.find('.post-date').html(posts[post].post_date);
                content.append($post);
            }
            // Scroll to the first element loaded
            content.animate({
                scrollTop: $('#post-' + posts[0].ID).offset().top + (content.scrollTop()- content.offset().top)
            }, 200);
        }

        // place the initial posts in the page
        postHandler(initialPosts);

        // add the click event to the more button
        more.click(function(){
            // Set the progress bar to 0%
            progressBar.css('width', '0%');
            // remove the more button innerHTML and insert the progress bar
            more.empty().append(progressElement);
            // AJAX REQUEST
            $.ajax({
                type: 'GET',
                // We do not want IE to cache the result
                cache: false,
                data: {
                    'start': start,
                    'desiredPosts': desiredPosts
                }
            }).success(function (data, text) {
                // parse the response (typeof data == String)
                data = JSON.parse(data);
                if (data.length > 0){
                    // Update the total number of items
                    start += data.length;
                    // Update the counter
                    counter.html(start);
                    // load items on the page
                    postHandler(data);
                }
                else{
                    $alert = alerts.requestEmpty;
                    // insert the empty message
                    widget.prepend($alert);
                    // Set the progress bar to 100%
                    progressBar.css('width', '100%');
                    // Remove the more button
                    window.setTimeout(function(){more.remove()}, 500);
                    // remove the empty message after 4 seconds
                    window.setTimeout(function(){$alert.remove()}, 4000);
                }
            }).error(function (request, status, error) {
                $alert = alerts.requestFailure;
                // insert the failure message
                widget.prepend($alert);
                // Set the progress bar to 100%
                progressBar.css('width', '100%');
                // Delay the normal more button to come back for a better effect
                window.setTimeout(function(){more.html('More <span class="caret"></span>')}, 500);
            });
        });
    });
</script>