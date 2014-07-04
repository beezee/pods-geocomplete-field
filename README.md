Pods GeoComplete
===========

Adds a field type of "GeoComplete" which uses the jQuery GeoComplete plugin found here: http://ubilabs.github.io/geocomplete/

Selected addresses are stored as serialized json, and expanded to stdClass objects when accessed.

The code below illustrates the properties available on this object and how you might use this in a template.

    $pod = pods('pod_type', get_the_id());
    $address = $pod->field('address');
    echo $address->address; // corresponds to the value the user selected from the geocomplete ui
    echo $address->lat; // geocoded latitude
    echo $address->lng; // geocoded longitude

Requires Pods 2.3.18 or later. (Please keep this notice in your plugin and set the appropriate version.)
