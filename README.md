# Nova Extension: Chronological Mission Posts

This extension provides a standardized mechanism for tracking the day and time of a post within a mission and a way of reading the entire mission in an eBook-style format ordered in chronological order based on the mission day and time of posts.

## Requirements

This extension requires:

- Nova 2.6+
- Nova Extension [`jquery`](https://github.com/jonmatterson/nova-ext-jquery)
- Nova Extension [`timepicker`](https://github.com/jonmatterson/nova-ext-timepicker)

## Installation

Copy the entire directory into `applications/extensions/chronological_mission_posts`.

Run the following command in your MySQL database:

```
ALTER TABLE nova_posts ADD COLUMN post_chronological_mission_post_day INTEGER NOT NULL DEFAULT 1;
ALTER TABLE nova_posts ADD COLUMN post_chronological_mission_post_time VARCHAR(4) NOT NULL DEFAULT '0000';
CREATE INDEX post_chronological_mission_post ON nova_posts (post_chronological_mission_post_day, post_chronological_mission_post_time);
```

Add the following to `application/config/extensions.php`:

```
$config['extensions']['enabled'][] = 'jquery';
$config['extensions']['enabled'][] = 'timepicker';
$config['extensions']['enabled'][] = 'chronological_mission_posts';
```

If you are already including `jquery` and/or `timepicker` in your extension config, you do not need to include them twice. Instead, simply ensure they are loaded before the `chronological_mission_posts` extension.

## Usage

This extension does the following:

* Replaces `Timeline` field in mission post creation with `Mission Day` and `Time` fields, where mission day should be an integer representing how many days into the mission is, and the time should be a value of the format `0000` through `2359`. It uses the `Timepicker` extension to help post writers specify the mission time.
* Replaces the  `Timeline` display in mission post viewing with a standard display of the mission day and time.
* Provides an eBook-style view for reading mission posts in chronological order. This can be accessed from a `Read Story` button when looking at the mission.

## Configuration

This extension supports several configuration options. To configure this extension, open `application/config/extensions.php` and add the following to the bottom of the file:

```
$config['extensions']['chronological_mission_posts'] = [];
```

Below that line, you can then specify additional options as described in the rest of this section.

If you would like to configure the timepicker, add the following:

```
$config['extensions']['chronological_mission_posts']['timepicker_options'] = [
   // your options...
];
```

The timepicker options can be found at the timepicker plugin website here:
https://timepicker.co/options/

For example, if you want to change the timepicker interval to be every 10 minutes instead of ever 30:

```
$config['extensions']['chronological_mission_posts']['timepicker_options'] = [
   'interval' => 30
];
```

One may also set different values for any of the strings and text used in the extension using any of the lines here (the default values are shown for reference):

```
$config['extensions']['chronological_mission_posts']['label_edit_day'] = 'Mission Day';
$config['extensions']['chronological_mission_posts']['label_edit_time'] = 'Time';
$config['extensions']['chronological_mission_posts']['label_view_prefix'] = 'Mission Day';
$config['extensions']['chronological_mission_posts']['label_view_concat'] = 'at';
$config['extensions']['chronological_mission_posts']['label_view_suffix'] = '';
$config['extensions']['chronological_mission_posts']['label_story_character_list'] = 'Featuring:';
$config['extensions']['chronological_mission_posts']['label_story_location'] = 'Location:';
$config['extensions']['chronological_mission_posts']['label_story_timeline'] = 'On:';
$config['extensions']['chronological_mission_posts']['label_story_back_to_mission'] = 'View Mission Details &raquo;';
$config['extensions']['chronological_mission_posts']['label_mission_read_story_link'] = 'Read Story &raquo;';
$config['extensions']['chronological_mission_posts']['label_mission_read_story_button'] = 'Read Story';
```

One can also disable either the Read Story button or link on the mission list page by setting either of those labels to `false`:

```
$config['extensions']['chronological_mission_posts']['label_mission_read_story_button'] = false;
$config['extensions']['chronological_mission_posts']['label_mission_read_story_link'] = false;
```

Finally, to change the style of the Read Story button, one may define styles through their skin for this CSS class:

```
.chronological_mission_posts--sim_missions--read-story {
    /* your styles... */
}
```

## Issues

If you encounter a bug or have a feature request, please report it on GitHub in the issue tracker here: https://github.com/jonmatterson/nova-ext-chronological_mission_posts/issues

## License

Copyright (c) 2018-2019 Jon Matterson.

This module is open-source software licensed under the **MIT License**. The full text of the license may be found in the `LICENSE` file.
