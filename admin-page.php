<?php

function get_timelines() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'pws_timelines';        
        $timelines = $wpdb->get_var("SELECT * FROM $table_name ORDER BY id DESC");        
        $query = "SELECT * FROM $table_name";
        $timelines = $wpdb->get_results($query, ARRAY_A);

        //echo "<br><br><br>";

        // Now $results contains an array of all records from the table
        foreach ($timelines as $timeline) {
                echo "<tr>";
                        echo "<td>" . $timeline['id'] . "</td>";
                        echo "<td>" . $timeline['timeline_title'] . "</td>";
                        echo "<td>" . "...actions..." . "</td>";
                echo "</tr>";
        // Access individual fields like $result['id'], $result['timeline_title'], etc.
        //echo $timeline['id'] . ': ' . $timeline['timeline_title'] . '<br>';
        }

        // "display_date": "",
// "safe_date": "",
// "title": "",
// "description": "",
// "order": 0
    
        return $data_value;
}
?>

<div class="wrap">
        <h1>Timelines</h1>
        <table class="pws-timeline-table">
                <tr>
                        <th>id</th>
                        <th>title</th>
                        <th>actions</th>
                </tr>
                <?php get_timelines(); ?>
        </table>
        
        <h2>Create new timeline</h2>
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

</div>