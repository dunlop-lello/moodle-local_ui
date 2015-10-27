local_ui
========

This plugin provides a framework for adding new re-usable UI components, logically layered between a plugin and a renderer.

Two types of subplugin are introduced:

 - model
 - widget

Models
======

Models are structured data representing widget configuration and state.

Widgets
=======

Widgets are UI components, designed to be used in the following ways:

 - As MoodleForm elements
 - As adminsetting elements
 - As generic HTML content

API stability and completeness
==============================

This should be considered alpha/beta quality at the moment, as there are a few questions I haven't answered yet around a few areas namely:

- JavaScript and progressive enhancement.
- Editors for configuring complex models. For example, a treeview widget could have an editor that allows drag-and-drop to re-order nodes, and this is seperate from normal input use allowing selection of individual nodes.

Contributions
=============

Contributions are welcomed, either as new widgets/models bundled in this repo, or as independant subplugins. Independant plugings are requested to use a 'vendor' prefix (e.g. DLC_xxx) to avoid future name clashes.

Please pause for thought if a bundled model or widget almost meets your needs and you're tempted to fork to change one line of code or copy/paste - I'd much rather address this by refactoring to make code more hookable via subclassing/method overrides. I'm happy if a patch comes from you for this, but will also consider requests for changes. However, in true Moodle tradition, I may get funny about punctuation in comments, variable names, or your choice of font.

Roadmap
=======

Coming soon:

- Colour picker.
- Range picker.
- Treeview components.

Ideally, the widget and model plugin types will be migrated into core.

