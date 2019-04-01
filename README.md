# Nova Extension: Chronological Mission Posts

This extension provides a standardized mechanism for tracking the day and time of a post within a mission and a way of reading the entire mission in an eBook-style format ordered in chronological order based on the mission day and time of posts.

Specifically, this extension does the following:

* Replaces `Timeline` field in mission post creation with `Mission Day` and `Time` fields, where mission day should be an integer representing how many days into the mission is, and the time should be a value of the format `0000` through `2359`. It uses the `Timepicker` extension to help post writers specify the mission time.
* Replaces the  `Timeline` display in mission post viewing with a standard display of the mission day and time.
* Provides an eBook-style view for reading mission posts in chronological order. This can be accessed from a `Read Story` button when looking at the mission.

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
