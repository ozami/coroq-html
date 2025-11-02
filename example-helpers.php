<?php
require 'vendor/autoload.php';

use function Coroq\Html\{
  h, p,  // Keep existing: h() wraps, p() prints
  para, h1, h2, div, span, a, button, input, select,
  ul, ol, li, form, label,
  externalLink, selectOptions
};

// Example 1: Existing functions still work
echo "=== Existing Functions ===\n";
p(h("Hello World"));  // Prints: Hello World
echo "\n\n";

// Example 2: New tag helpers - para() for <p> tag
echo "=== Para (paragraph) Tag ===\n";
$paragraph = para()
  ->addClass('intro')
  ->append('This is a paragraph using para() helper.');
p($paragraph);  // Use p() to print!
echo "\n\n";

// Example 3: Headings
echo "=== Headings ===\n";
p(h1()->append('Main Title'));
p(h2()->append('Subtitle'));
echo "\n\n";

// Example 4: Complex nested structure
echo "=== Complex Structure ===\n";
$page = div()
  ->addClass('container')
  ->append(
    h1()->append('Welcome')
  )
  ->append(
    para()->addClass('lead')->append('This is the introduction.')
  )
  ->append(
    ul()
      ->append(li()->append('First item'))
      ->append(li()->append('Second item'))
      ->append(li()->append('Third item'))
  );

p($page);
echo "\n\n";

// Example 5: Links with external link helper
echo "=== External Link ===\n";
$link = a()
  ->apply(externalLink('https://example.com'))
  ->append('Visit Example');
p($link);
echo "\n\n";

// Example 6: Form with inputs
echo "=== Form ===\n";
$formHtml = form('/submit')
  ->append(
    div()->addClass('field')
      ->append(label('email')->append('Email:'))
      ->append(input('email', 'email')->placeholder('Enter email'))
  )
  ->append(
    div()->addClass('field')
      ->append(label('country')->append('Country:'))
      ->append(
        select()->attr('name', 'country')->apply(
          selectOptions(['us' => 'USA', 'uk' => 'UK', 'jp' => 'Japan'], 'uk')
        )
      )
  )
  ->append(button('submit')->append('Submit'));

p($formHtml);
echo "\n\n";

// Example 7: Using each() helper
echo "=== Using each() ===\n";
$users = [
  ['name' => 'Alice', 'role' => 'Admin'],
  ['name' => 'Bob', 'role' => 'User'],
];

$userList = ul()->each($users, function($parent, $user) {
  $parent->append(
    li()->append("{$user['name']} - {$user['role']}")
  );
});

p($userList);
echo "\n\n";
