# array-editor

Example code only. This will be replaced

```PHP
require_once('./vendor/autoload.php');

$data = [
    'one' => [
        'two' => [
            'three' => 'third value'
        ]
    ],
    'four' => 'fourth value'
];

$editor = new \Funeralzone\ArrayEditor\Editor($data);

var_dump($editor->all());

$editor->edit(
    [
        'one',
        'two',
        'three'
    ],
    'updated third value'
);

$editor->insertArrayItem(
    [
        'one',
        'two',
    ],
    'fifth value',
    'five'
);

$editor->editArrayItem(
    [
        'one',
        'two',
        new Funeralzone\ArrayEditor\ArrayIndexFinders\FindByStaticIndex('five')
    ],
    'updated fifth value'
);

$editor->insertArrayItem(
    [
        'one',
        'two',
    ],
    [
        [
            'id' => 1,
            'value' => 'first sub value'
        ],
        [
            'id' => 2,
            'value' => 'second sub value'
        ],
        [
            'id' => 3,
            'value' => 'third sub value'
        ],
    ],
    'six'
);

$editor->editArrayItem(
    [
        'one',
        'two',
        'six',
        new Funeralzone\ArrayEditor\ArrayIndexFinders\FindByArrayItemProperty('id', 2),
        'value'
    ],
    'updated second sub value'
);

$editor->deleteArrayItem(
    [
        'one',
        'two',
    ],
    new Funeralzone\ArrayEditor\ArrayIndexFinders\FindByStaticIndex('six')
);

var_dump($editor->all());
```