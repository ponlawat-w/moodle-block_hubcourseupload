<?php
$capabilities = array(
    'block/hubcourseupload:addinstance' => array(
        'riskbitmask' => 0,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array()
    ),
    'block/hubcourseupload:myaddinstance' => array(
        'riskbitmask' => 0,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array()
    ),
    'block/hubcourseupload:upload' => array(
        'riskbitmask' => RISK_PERSONAL | RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_USER,
        'archetypes' => array(
            'user' => CAP_ALLOW
        )
    )
);