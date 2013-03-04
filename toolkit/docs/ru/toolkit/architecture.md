# Архитектура пакета Toolkit
## Диаграмма классов
![Alt-Диаграмма классов](http://wiki.limb-project.com/2011.1/lib/exe/fetch.php?cache=&media=limb3:ru:packages:toolkit:limb_3_x_toolkit.png)

## Описание классов пакета Toolkit
Класс | Назначение
------|-----------
[lmb_registry](./lmb_registry.md) | Общедоступный Реестр. Хранит любые данные. Используется классом lmbToolkit для сохранения и восстановления. При желании может использоваться отдельно от остальных классов пакета.
[lmb_toolkit](./lmb_toolkit.md)	| Часто называется инструментарием. Содержит набор инструментов tools, которым делегирует обязанности. Клиенты ничего не знают об инструментах и работает так, как будто все нужные методы есть в lmbToolkit.
[lmb_static_tools](./lmb_static_tools.md) | Набор инструментов, который всегда возвращает предопределенный результат. Часто используется в тестах для изменения поведения других инструментов
[lmb_abstract_tools](./lmb_abstract_tools.md) | Абстрактный класс, который используется при создании своих наборов инструментов. Возвращает из getToolsSignatures все методы, которые есть в классе
[lmb_mock_tools_wrapper](./lmb_mock_tools_wrapper.md) | Враппер для внедрения моков в инструментарий. Позволяет создавать моки на другие набор инструментов, но также указывать, какие методы делегировать моку, а какие нет.

Этот класс используется в lmbTookit :: extend()|