<?php
use Coroq\Html\Html;
use PHPUnit\Framework\TestCase;

/**
 * @covers Coroq\Html\Html
 */
class HtmlTest extends TestCase
{
  const specials = "&<>'\"";
  const escaped = "&amp;&lt;&gt;&#039;&quot;";

  public function testEscape()
  {
    foreach (["", null, "&", "<", ">", "あ", "a"] as $s) {
      $this->assertSame(
        htmlspecialchars($s, ENT_QUOTES, "UTF-8"),
        Html::escape($s)
      );
    }
  }
  
  public function test__toStringCanEscapeEmptyString()
  {
    $this->assertSame("", (new Html())->__toString());
  }
  
  public function test__toStringCanEscapeSpecialCharacters()
  {
    $this->assertSame(
      self::escaped,
      (new Html())->append(self::specials)->__toString()
    );
  }
  
  public function test__toStringCanConstructTag()
  {
    $this->assertSame(
      "<p></p>",
      (new Html())->tag("p")->__toString()
    );
  }
  
  public function test__toStringCanConstructSelfCloseTag()
  {
    $this->assertSame(
      "<br />",
      (new Html())->tag("br")->close(Html::SELF_CLOSE)->__toString()
    );
  }

  public function test__toStringCanConstructTagWithAttributes()
  {
    $this->assertSame(
      '<br id="test" clear="both">',
      (new Html())->tag("br")->id("test")->attr("clear", "both")->__toString()
    );
  }

  public function test__toStringCanConstructTagContainsText()
  {
    $this->assertSame(
      "<p>" . self::escaped . "</p>",
      (new Html())->tag("p")->append(self::specials)->__toString()
    );
  }
  
  public function testTagCanSetTag()
  {
    $this->assertSame(
      "test",
      (new Html())->tag("test")->getTag()
    );
  }
  
  public function testTagCanUnSetTag()
  {
    $this->assertSame(
      "",
      (new Html())->tag("test")->tag("")->getTag()
    );
  }
  
  public function testTagThrowsExceptionForInvalidTagName()
  {
    foreach (str_split("!\"#$%&'()=^~\\|@`[{}];+*,<>/?\r\n\t ") as $c) {
      try {
        (new Html())->tag($c);
        $this->fail("Could not ban '$c'");
      }
      catch (\Exception $e) {
        $this->assertTrue($e instanceof \InvalidArgumentException);
      }
    }
  }

  public function testWrapWithNullCreatesEmptyWrapper()
  {
    $inner = (new Html())->tag("span")->append("Text");
    $result = $inner->wrap(null);
    $this->assertSame(
      "<span>Text</span>",
      $result->__toString()
    );
  }

  public function testWrapWithConfiguredWrapper()
  {
    $inner = (new Html())->tag("span")->append("Text");
    $wrapper = (new Html())->tag("div")->addClass("container");
    $result = $inner->wrap($wrapper);
    $this->assertSame(
      '<div class="container"><span>Text</span></div>',
      $result->__toString()
    );
  }

  public function testWrapReturnsWrapper()
  {
    $inner = (new Html())->tag("span")->append("Text");
    $wrapper = (new Html())->tag("div");
    $result = $inner->wrap($wrapper);
    $this->assertSame($wrapper, $result);
  }

  public function testWrapCanChainOnWrapper()
  {
    $inner = (new Html())->tag("span")->append("Text");
    $result = $inner->wrap((new Html())->tag("div"))->addClass("test");
    $this->assertSame(
      '<div class="test"><span>Text</span></div>',
      $result->__toString()
    );
  }

  public function testWrapWithEmptyWrapper()
  {
    $inner = (new Html())->tag("p")->append("Content");
    $result = $inner->wrap((new Html())->tag("section"));
    $this->assertSame(
      '<section><p>Content</p></section>',
      $result->__toString()
    );
  }

  public function testWrapPreservesInnerElementAttributes()
  {
    $inner = (new Html())->tag("a")->attr("href", "#")->append("Link");
    $result = $inner->wrap((new Html())->tag("li"));
    $this->assertSame(
      '<li><a href="#">Link</a></li>',
      $result->__toString()
    );
  }

  public function testDataSetsDataAttribute()
  {
    $this->assertSame(
      '<div data-user-id="123"></div>',
      (new Html())->tag("div")->data("user-id", 123)->__toString()
    );
  }

  public function testDataWithMultipleAttributes()
  {
    $html = (new Html())
      ->tag("div")
      ->data("user-id", 123)
      ->data("role", "admin")
      ->data("status", "active");
    $this->assertSame(
      '<div data-user-id="123" data-role="admin" data-status="active"></div>',
      $html->__toString()
    );
  }

  public function testDataWithBooleanValue()
  {
    $html = (new Html())
      ->tag("div")
      ->data("active", true);
    $this->assertSame(
      '<div data-active></div>',
      $html->__toString()
    );
  }

  public function testDataEscapesValue()
  {
    $this->assertSame(
      '<div data-content="' . self::escaped . '"></div>',
      (new Html())->tag("div")->data("content", self::specials)->__toString()
    );
  }

  public function testDataCanChain()
  {
    $html = (new Html())->tag("button")->data("action", "submit")->addClass("btn");
    $this->assertSame(
      '<button data-action="submit" class="btn"></button>',
      $html->__toString()
    );
  }

  public function testWhenExecutesCallbackWhenTrue()
  {
    $html = (new Html())
      ->tag("div")
      ->when(true, function($el) {
        $el->addClass("active");
      });
    $this->assertSame(
      '<div class="active"></div>',
      $html->__toString()
    );
  }

  public function testWhenSkipsCallbackWhenFalse()
  {
    $html = (new Html())
      ->tag("div")
      ->when(false, function($el) {
        $el->addClass("active");
      });
    $this->assertSame(
      '<div></div>',
      $html->__toString()
    );
  }

  public function testWhenCanChain()
  {
    $isAdmin = true;
    $isPremium = false;
    $html = (new Html())
      ->tag("a")
      ->attr("href", "/profile")
      ->when($isAdmin, function($el) {
        $el->addClass("admin-link");
      })
      ->when($isPremium, function($el) {
        $el->addClass("premium-badge");
      })
      ->append("Profile");
    $this->assertSame(
      '<a href="/profile" class="admin-link">Profile</a>',
      $html->__toString()
    );
  }

  public function testWhenWithMultipleOperations()
  {
    $html = (new Html())
      ->tag("button")
      ->when(true, function($el) {
        $el->addClass("btn")->addClass("btn-primary")->data("role", "submit");
      });
    $this->assertSame(
      '<button class="btn btn-primary" data-role="submit"></button>',
      $html->__toString()
    );
  }

  public function testWhenReturnsThis()
  {
    $html = (new Html())->tag("div");
    $result = $html->when(true, function($el) {
      $el->addClass("test");
    });
    $this->assertSame($html, $result);
  }

  public function testWhenWithDynamicCondition()
  {
    $userRole = "admin";
    $html = (new Html())
      ->tag("nav")
      ->when($userRole === "admin", function($el) {
        $el->append((new Html())->tag("a")->attr("href", "/admin")->append("Admin Panel"));
      })
      ->when($userRole === "user", function($el) {
        $el->append((new Html())->tag("a")->attr("href", "/dashboard")->append("Dashboard"));
      });
    $this->assertSame(
      '<nav><a href="/admin">Admin Panel</a></nav>',
      $html->__toString()
    );
  }

  public function testApplyExecutesCallback()
  {
    $html = (new Html())
      ->tag("div")
      ->apply(function($el) {
        $el->addClass("applied");
      });
    $this->assertSame(
      '<div class="applied"></div>',
      $html->__toString()
    );
  }

  public function testApplyWithExtraArguments()
  {
    $html = (new Html())
      ->tag("div")
      ->apply(function($el, $width, $color) {
        $el->data("width", $width)->data("color", $color);
      }, 2, "red");
    $this->assertSame(
      '<div data-width="2" data-color="red"></div>',
      $html->__toString()
    );
  }

  public function testApplyReturnsThis()
  {
    $html = (new Html())->tag("div");
    $result = $html->apply(function($el) {
      $el->addClass("test");
    });
    $this->assertSame($html, $result);
  }

  public function testApplyWithNamedFunction()
  {
    $makeButton = function($el) {
      $el->addClass("btn")->addClass("btn-primary")->attr("type", "button");
    };
    $html = (new Html())
      ->tag("button")
      ->apply($makeButton)
      ->append("Click");
    $this->assertSame(
      '<button class="btn btn-primary" type="button">Click</button>',
      $html->__toString()
    );
  }

  public function testEachWithSimpleArray()
  {
    $items = ["Apple", "Banana", "Cherry"];
    $html = (new Html())
      ->tag("ul")
      ->each($items, function($parent, $item) {
        $parent->append((new Html())->tag("li")->append($item));
      });
    $this->assertSame(
      '<ul><li>Apple</li><li>Banana</li><li>Cherry</li></ul>',
      $html->__toString()
    );
  }

  public function testEachWithAssociativeArray()
  {
    $items = ["name" => "John", "age" => "30", "city" => "NYC"];
    $html = (new Html())
      ->tag("dl")
      ->each($items, function($parent, $value, $key) {
        $parent->append((new Html())->tag("dt")->append($key));
        $parent->append((new Html())->tag("dd")->append($value));
      });
    $this->assertSame(
      '<dl><dt>name</dt><dd>John</dd><dt>age</dt><dd>30</dd><dt>city</dt><dd>NYC</dd></dl>',
      $html->__toString()
    );
  }

  public function testEachWithEmptyArray()
  {
    $html = (new Html())
      ->tag("ul")
      ->each([], function($parent, $item) {
        $parent->append((new Html())->tag("li")->append($item));
      });
    $this->assertSame(
      '<ul></ul>',
      $html->__toString()
    );
  }

  public function testEachReturnsThis()
  {
    $html = (new Html())->tag("ul");
    $result = $html->each(["a", "b"], function($parent, $item) {
      $parent->append((new Html())->tag("li")->append($item));
    });
    $this->assertSame($html, $result);
  }

  public function testEachCanChain()
  {
    $html = (new Html())
      ->tag("ul")
      ->addClass("list")
      ->each(["Item 1", "Item 2"], function($parent, $item) {
        $parent->append((new Html())->tag("li")->append($item));
      })
      ->id("main-list");
    $this->assertSame(
      '<ul class="list" id="main-list"><li>Item 1</li><li>Item 2</li></ul>',
      $html->__toString()
    );
  }

  public function testEachWithComplexStructure()
  {
    $users = [
      ["name" => "Alice", "role" => "Admin"],
      ["name" => "Bob", "role" => "User"],
    ];
    $html = (new Html())
      ->tag("table")
      ->each($users, function($parent, $user) {
        $row = (new Html())
          ->tag("tr")
          ->append((new Html())->tag("td")->append($user["name"]))
          ->append((new Html())->tag("td")->append($user["role"]));
        $parent->append($row);
      });
    $this->assertSame(
      '<table><tr><td>Alice</td><td>Admin</td></tr><tr><td>Bob</td><td>User</td></tr></table>',
      $html->__toString()
    );
  }

  public function testEachWithIndexAccess()
  {
    $items = ["First", "Second", "Third"];
    $html = (new Html())
      ->tag("ol")
      ->each($items, function($parent, $item, $index) {
        $parent->append(
          (new Html())
            ->tag("li")
            ->data("index", $index)
            ->append($item)
        );
      });
    $this->assertSame(
      '<ol><li data-index="0">First</li><li data-index="1">Second</li><li data-index="2">Third</li></ol>',
      $html->__toString()
    );
  }
}
