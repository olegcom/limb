# Использование пакета Toolkit
## Использование lmbToolkit
Основным классом является [lmb_toolkit](./lmb_toolkit.md). lmbToolkit реализован в виде Singleton. lmbToolkit делегирует большинство операций инструментам Tools. Tools необходимо регистрировать в lmbToolkit. Обычно это происходит в файле package.php пакетов, который вызывается автоматически.

Например:

    lmb_require('toolkit/lmbWebAppTools.class.php');
    lmbToolkit :: merge(new lmbWebAppTools());

lmbWebAppTools поддерживает метод getRequest(). Пример клиентского кода, который использует возможности lmbWebAppTools:

    $toolkit = lmbToolkit :: instance();
    $request = $toolkit->getRequest();

Мы могли бы также зарегистрировать другой tools, который также поддерживает метод getRequest(), но реализация этого метода в другой tools — иная. Для клиентского кода эта подмена будет незаметной.

## Создание своих наборов инструментов (tools).

При разработке дополнительных пакетов на базе Limb часто появляется потребность создать отдельный набор инструментов (tools). tools должны поддерживать метод getToolsSignatures(), который возвращает массив вида ('имя метода' ⇒ 'объект, поддерживающий этот метод'). Чтобы не реализовывать этот метод самостоятельно, большинство реализуемых наборов инструментов наследуется от lmbAbstractTools, который формирует этот массив автоматически из публичных методов класса.

Например:

    lmb_require('toolkit/lmbAbstractTools.class.php');
    lmb_require('tree/lmbMaterializedPathTree.class.php');
 
    class lmbTreeTools extends lmbAbstractTools
    {
      protected $tree;
 
      function getTree()
      {
        if(is_object($this->tree))
          return $this->tree;
 
        $this->tree = new lmbMaterializedPathTree();
 
        return $this->tree;
      }
 
      function setTree($tree)
      {
        $this->tree = $tree;
      }
    }
    ?> 

Tools, которые представляют методы для получения часто используемых объектов, например, как Tree для пакета TREE, обычно содержат как getter-ы, так и setter-ы. Это очень удобно для модульного тестирования, см. ниже.

## Регистрация tools в lmbToolkit
Для регистрации набора в lmbToolkit используется 2 различных метода:

* lmbToolkit :: extend()
* lmbToolkit :: merge()

Отличия в них следующие: **extend()** проверяет, не пересекаются ли поддерживаемые методы различных tools, то есть новый tools, которые регистрируется в **lmbToolkit** не должен поддерживать методы, которые уже поддерживаются другими. **merge()** такую проверку не делает, при этом преимущество получает tools, который регистрируется последним.

В большинстве пакетов в настоящее время используется merge().

    FooTools extends lmbAbstractTools
    {
      function doSomething()
      {
        return "foo";
      }
    }
    BarTools extends lmbAbstractTools
    {
      function doSomething()
      {
        return "bar";
      }
    }
    BazTools extends lmbAbstractTools
    {
      function doSomething()
      {
        return "baz";
      }
    }
 
    lmbToolkit::merge(new FooTools());
    $var = lmbToolkit::instance()->doSomething(); //foo
 
    lmbToolkit::extend(new BarTools());
    $var = lmbToolkit::instance()->doSomething(); //foo
 
    lmbToolkit::merge(new BazTools());
    $var = lmbToolkit::instance()->doSomething(); //baz

## Использование Toolkit-а в тестах
Основная цель использования [lmb_toolkit](./lmb_toolkit.md) — иметь возможность подмены объектов, которые используются в клиентском коде проектов, другими без изменения клиентского кода. Эту возможность можно использовать в модульном тестировании.

Еще одно применение lmbToolkit — это облегчение изоляции одних тестов от других. lmbToolkit поддерживает методы save() и restore(), которые позволяют создавать новые копии инструментов во время теста, а затем — удалять их, восстанавливая прежнее состояние.

Объединяя эти знания, получаем следующее использование lmbToolkit в тестах. Обычно в setUp() создается новая копия всего набора инструметов путем вызова save(). Затем в этот новый набор добавляются объекты, поведение которых нам бы хотелось контролировать в тестах, например, мок-объекты. В методе tearDown() мы вызываем restore(). Ниже дам пример теста на класс, который использует дерево через инструментарий, который мы указали выше. Обратите внимание, что мы использовали метод setTree() набора инструментов. Это значительно облегчает возможность изоляции тестов.

    Mock :: generate('lmbTree', 'MockTree');
 
    class SomeClassTest extends UnitTestCase
    {
      protected $tree;
 
      function setUp()
      {
        $tree = new MockTree();
        $toolkit = lmbToolkit :: save();
        $toolkit->setTree($tree);
      }
 
      function tearDown()
      {
        lmbToolkit :: restore();
      }
      [...]
    }

Конечно, lmbTreeTools, который поддерживает метод setTree() должен был бы уже зарегистрирован в lmbToolkit.

Если ваш набор инструментов не поддерживает setter-ы или же нужно более тщательно контролировать поведение tools в тестах, можно воспользоваться возможностями класса [lmb_mock_tools_wrapper](./lmb_mock_tools_wrapper.md).
