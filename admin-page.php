<?php session_start(); ?>

<div class="wrap">

<?php

        if (isset($_GET['timeline'])) {
                // viewing single
                echo "<a href=''>View all timelines</a>";
                echo "<h1>Viewing: " . $_GET['timeline'];

                function get_single_timeline($timeline_id) {
                        global $wpdb;
                        $table_name = $wpdb->prefix . 'pws_timelines';                        
                        $query = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $timeline_id);

                        // Get the result
                        $result = $wpdb->get_row($query);

                        // Check if a row was found
                        if ($result) {
                                echo "-------------------------------";
                                print_r($result);
                                echo "-------------------------------";
                                // // Access the data
                                // $id = $result->id;
                                // $timeline_title = $result->timeline_title;

                                // // Do something with the data
                                // echo "ID: $id, Title: $timeline_title";
                        } else {
                                return null;
                        }
                    
                        // Retrieve the data from the custom table
                        $timeline = $wpdb->get_var("SELECT data_value FROM $table_name ORDER BY id DESC LIMIT 1");
                    
                    
                        return $timeline;
                } // end get_single_timeline

                $timeline = get_single_timeline($timeline_id);

                // "display_date": "",
                // "safe_date": "",
                // "title": "",
                // "description": "",
                // "order": 0


        ?>

                <form class="pws-edit-timeline-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                        <?php wp_nonce_field('handle_timeline_form_nonce', '_wpnonce'); ?>

                        <input type="hidden" name="action" value="handle_timeline_form_hook">
                        
                        <label for="timeline_title">Timeline name:</label><br />
                        <input id="timeline_title" type="text" name="timeline_title" value="<?php echo $timeline->title ?? ''; ?>"><br />

                        <button class="pws-btn">
                                Update
                        </button>

                </form>


        <?php
                
        } else {
                // viewing all

                function get_timelines() {
                        global $wpdb;
                        $table_name = $wpdb->prefix . 'pws_timelines';        
                        $timelines = $wpdb->get_var("SELECT * FROM $table_name ORDER BY id DESC");        
                        $query = "SELECT * FROM $table_name";
                        $timelines = $wpdb->get_results($query, ARRAY_A);
        
                        // Now $results contains an array of all records from the table
                        foreach ($timelines as $timeline) {
        
                                $single_route = add_query_arg('timeline', $timeline['id'], wp_get_referer());
        
        
                                echo "<tr>";
                                        echo "<td>" . $timeline['id'] . "</td>";
                                        echo "<td>" . $timeline['timeline_title'] . "</td>";
                                        echo "<td><a class='timeline-btn' href='$single_route'>Details</a></td>";
                                echo "</tr>";
                        // Access individual fields like $result['id'], $result['timeline_title'], etc.
                        //echo $timeline['id'] . ': ' . $timeline['timeline_title'] . '<br>';
                        }
        
             
                
                } 
             

                if (isset($_SESSION['success'])) {
                        $message = sanitize_text_field($_SESSION['success']);
                        echo '<div class="success-message">' . esc_html($message) . '</div>';
                        unset($_SESSION['success']); // Clear the session variable after displaying the message
                }
           
?>                
                <button class="js-toggle-create-form pws-btn">
                        Create new timeline
                </button>

                <form class="pws-create-timeline-form hide" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                        <?php wp_nonce_field('handle_timeline_form_nonce', '_wpnonce'); ?>

                        <input type="hidden" name="action" value="handle_timeline_form_hook">
                        
                        <label for="timeline_title">Timeline name:</label><br />
                        <input id="timeline_title" type="text" name="timeline_title"><br />

                        <button class="pws-btn">
                                Create
                        </button>

                </form>

                <h1>Existing timelines</h1>
                <table class="pws-timeline-table">
                        <tr>
                                <th>id</th>
                                <th>title</th>
                                <th>actions</th>
                        </tr>
                        <?php get_timelines(); ?>
                </table>

<?php   } // end if/else ?>

</div>