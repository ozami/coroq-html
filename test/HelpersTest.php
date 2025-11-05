<?php
use PHPUnit\Framework\TestCase;
use Coroq\Html\Html;
use function Coroq\Html\{
  h, p, noEscape,
  para, heading, h1, h2, h3, h4, h5, h6,
  div, span, small, a, button, input, select, option, textarea, label, form,
  ul, ol, li, table, thead, tbody, tr, th, td, img, iframe, time, br, hr,
  script, scriptData,
  externalLink, selectOptions
};

/**
 * @covers Coroq\Html\h
 * @covers Coroq\Html\p
 * @covers Coroq\Html\noEscape
 * @covers Coroq\Html\para
 * @covers Coroq\Html\heading
 * @covers Coroq\Html\h1
 * @covers Coroq\Html\h2
 * @covers Coroq\Html\h3
 * @covers Coroq\Html\h4
 * @covers Coroq\Html\h5
 * @covers Coroq\Html\h6
 * @covers Coroq\Html\div
 * @covers Coroq\Html\span
 * @covers Coroq\Html\small
 * @covers Coroq\Html\a
 * @covers Coroq\Html\button
 * @covers Coroq\Html\input
 * @covers Coroq\Html\select
 * @covers Coroq\Html\option
 * @covers Coroq\Html\textarea
 * @covers Coroq\Html\label
 * @covers Coroq\Html\form
 * @covers Coroq\Html\ul
 * @covers Coroq\Html\ol
 * @covers Coroq\Html\li
 * @covers Coroq\Html\table
 * @covers Coroq\Html\thead
 * @covers Coroq\Html\tbody
 * @covers Coroq\Html\tr
 * @covers Coroq\Html\th
 * @covers Coroq\Html\td
 * @covers Coroq\Html\img
 * @covers Coroq\Html\iframe
 * @covers Coroq\Html\time
 * @covers Coroq\Html\br
 * @covers Coroq\Html\hr
 * @covers Coroq\Html\script
 * @covers Coroq\Html\scriptData
 * @covers Coroq\Html\externalLink
 * @covers Coroq\Html\selectOptions
 */
class HelpersTest extends TestCase
{
  public function testH()
  {
    $html = h('test');
    $this->assertInstanceOf(\Coroq\Html\Html::class, $html);
    $this->assertSame('test', (string)$html);

    // h() should return Html instance as-is
    $existing = new \Coroq\Html\Html();
    $this->assertSame($existing, h($existing));
  }

  public function testNoEscape()
  {
    $raw = noEscape('<strong>bold</strong>');
    $this->assertInstanceOf(\Coroq\Html\NoEscape::class, $raw);
    $this->assertSame('<strong>bold</strong>', (string)$raw);

    // Test that it's not escaped when appended
    $html = div()->append($raw);
    $this->assertSame('<div><strong>bold</strong></div>', (string)$html);
  }

  public function testPara()
  {
    $this->assertSame(
      '<p>Text</p>',
      para()->append('Text')->__toString()
    );
  }

  public function testHeading()
  {
    $this->assertSame('<h1>Title</h1>', heading(1)->append('Title')->__toString());
    $this->assertSame('<h2>Subtitle</h2>', heading(2)->append('Subtitle')->__toString());
    $this->assertSame('<h3>Section</h3>', heading(3)->append('Section')->__toString());
  }

  public function testH1()
  {
    $this->assertSame('<h1>Title</h1>', h1()->append('Title')->__toString());
  }

  public function testH2()
  {
    $this->assertSame('<h2>Subtitle</h2>', h2()->append('Subtitle')->__toString());
  }

  public function testH3()
  {
    $this->assertSame('<h3>Section</h3>', h3()->append('Section')->__toString());
  }

  public function testH4()
  {
    $this->assertSame('<h4>Subsection</h4>', h4()->append('Subsection')->__toString());
  }

  public function testH5()
  {
    $this->assertSame('<h5>Minor</h5>', h5()->append('Minor')->__toString());
  }

  public function testH6()
  {
    $this->assertSame('<h6>Small</h6>', h6()->append('Small')->__toString());
  }

  public function testDiv()
  {
    $this->assertSame(
      '<div class="container">Content</div>',
      div()->addClass('container')->append('Content')->__toString()
    );
  }

  public function testSpan()
  {
    $this->assertSame(
      '<span class="highlight">Text</span>',
      span()->addClass('highlight')->append('Text')->__toString()
    );
  }

  public function testA()
  {
    $this->assertSame('<a>Link</a>', a()->append('Link')->__toString());
    $this->assertSame(
      '<a href="/home">Home</a>',
      a('/home')->append('Home')->__toString()
    );
    $this->assertSame(
      '<a href="/home" target="_blank">Home</a>',
      a('/home', '_blank')->append('Home')->__toString()
    );
  }

  public function testButton()
  {
    $this->assertSame(
      '<button type="button">Click</button>',
      button()->append('Click')->__toString()
    );
    $this->assertSame(
      '<button type="submit">Submit</button>',
      button('submit')->append('Submit')->__toString()
    );
  }

  public function testInput()
  {
    $this->assertSame('<input>', input()->__toString());
    $this->assertSame('<input type="text">', input('text')->__toString());
    $this->assertSame('<input type="email">', input('email')->__toString());
    $this->assertSame(
      '<input type="email" name="email">',
      input('email', 'email')->__toString()
    );
  }

  public function testSelect()
  {
    $this->assertSame('<select></select>', select()->__toString());
  }

  public function testOption()
  {
    $this->assertSame('<option></option>', option()->__toString());
    $this->assertSame(
      '<option value="1">One</option>',
      option('1', 'One')->__toString()
    );
  }

  public function testTextarea()
  {
    $this->assertSame('<textarea></textarea>', textarea()->__toString());
    $this->assertSame(
      '<textarea name="bio"></textarea>',
      textarea('bio')->__toString()
    );
  }

  public function testLabel()
  {
    $this->assertSame('<label>Name</label>', label()->append('Name')->__toString());
    $this->assertSame(
      '<label for="email">Email</label>',
      label('email')->append('Email')->__toString()
    );
  }

  public function testForm()
  {
    $this->assertSame('<form></form>', form()->__toString());
    $this->assertSame(
      '<form action="/submit"></form>',
      form('/submit')->__toString()
    );
    $this->assertSame(
      '<form action="/submit" method="post"></form>',
      form('/submit', 'post')->__toString()
    );
    $this->assertSame(
      '<form action="/search" method="get"></form>',
      form('/search', 'get')->__toString()
    );
  }

  public function testUl()
  {
    $this->assertSame('<ul></ul>', ul()->__toString());
  }

  public function testOl()
  {
    $this->assertSame('<ol></ol>', ol()->__toString());
  }

  public function testLi()
  {
    $this->assertSame('<li>Item</li>', li()->append('Item')->__toString());
  }

  public function testTable()
  {
    $this->assertSame('<table></table>', table()->__toString());
  }

  public function testThead()
  {
    $this->assertSame('<thead></thead>', thead()->__toString());
  }

  public function testTbody()
  {
    $this->assertSame('<tbody></tbody>', tbody()->__toString());
  }

  public function testTr()
  {
    $this->assertSame('<tr></tr>', tr()->__toString());
  }

  public function testTh()
  {
    $this->assertSame('<th>Header</th>', th()->append('Header')->__toString());
  }

  public function testTd()
  {
    $this->assertSame('<td>Data</td>', td()->append('Data')->__toString());
  }

  public function testImg()
  {
    $this->assertSame('<img>', img()->__toString());
    $this->assertSame(
      '<img src="photo.jpg">',
      img('photo.jpg')->__toString()
    );
    $this->assertSame(
      '<img src="photo.jpg" alt="A photo">',
      img('photo.jpg', 'A photo')->__toString()
    );
  }

  public function testIframe()
  {
    $this->assertSame('<iframe></iframe>', iframe()->__toString());
    $this->assertSame(
      '<iframe src="https://example.com"></iframe>',
      iframe('https://example.com')->__toString()
    );
  }

  public function testTime()
  {
    // Empty time tag
    $this->assertSame('<time></time>', time()->__toString());

    // Timestamp without format
    $result = time(timestamp: 1699000000);
    $this->assertStringContainsString('<time datetime="', (string)$result);
    $this->assertStringContainsString('</time>', (string)$result);

    // Timestamp with format
    $result = time(timestamp: 1699000000, format: 'Y-m-d');
    $this->assertStringContainsString('datetime=', (string)$result);
    $this->assertStringContainsString('>2023-11-03<', (string)$result);

    // DateTime object
    $dt = new \DateTime('2023-11-03 10:30:00', new \DateTimeZone('UTC'));
    $result = time(datetime: $dt);
    $this->assertStringContainsString('datetime="2023-11-03T10:30:00+00:00"', (string)$result);

    // DateTime with format
    $result = time(datetime: $dt, format: 'F j, Y');
    $this->assertStringContainsString('>November 3, 2023<', (string)$result);

    // ISO 8601 string
    $result = time(iso8601: '2023-11-03T10:30:00+00:00');
    $this->assertSame(
      '<time datetime="2023-11-03T10:30:00+00:00">2023-11-03T10:30:00+00:00</time>',
      (string)$result
    );
  }

  public function testSmall()
  {
    $this->assertSame(
      '<small>Fine print</small>',
      small()->append('Fine print')->__toString()
    );
  }

  public function testBr()
  {
    $this->assertSame('<br>', br()->__toString());
  }

  public function testHr()
  {
    $this->assertSame('<hr>', hr()->__toString());
  }

  public function testExternalLink()
  {
    $link = a()->apply(externalLink('https://example.com'));
    $this->assertSame(
      '<a href="https://example.com" target="_blank" rel="noopener noreferrer"></a>',
      $link->__toString()
    );
  }

  public function testSelectOptions()
  {
    $options = ['1' => 'One', '2' => 'Two', '3' => 'Three'];
    $sel = select()->apply(selectOptions($options, '2'));
    $this->assertSame(
      '<select><option value="1">One</option><option value="2" selected>Two</option><option value="3">Three</option></select>',
      $sel->__toString()
    );
  }

  public function testSelectOptionsMultiple()
  {
    $options = ['a' => 'Alpha', 'b' => 'Beta', 'c' => 'Gamma'];
    $sel = select()->apply(selectOptions($options, ['a', 'c']));
    $this->assertSame(
      '<select><option value="a" selected>Alpha</option><option value="b">Beta</option><option value="c" selected>Gamma</option></select>',
      $sel->__toString()
    );
  }

  public function testScriptDataCanEncodeScalarValue()
  {
    foreach (["text", 1, false, null] as $value) {
      $h = scriptData("test", $value);
      $encoded = base64_encode(json_encode($value));
      $this->assertSame(
        "<script>const test = JSON.parse(atob('$encoded'));</script>",
        (string)$h
      );
    }
  }
}
