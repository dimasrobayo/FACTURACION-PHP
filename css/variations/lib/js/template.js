/* template.js, Copyright (C) 2007 - 2010 YOOtheme GmbH */

var YOOTemplate = {
		
	start: function() {

		/* Match height of div tags */
		matchHeights();

		/* Accordion menu */
		new YOOAccordionMenu('div#middle ul.menu li.toggler', 'ul.accordion', { accordion: 'slide' });

		/* Dropdown menu */
		var dropdown = new YOODropdownMenu('menu', { mode: 'default', dropdownSelector: 'div.dropdown', transition: Fx.Transitions.Expo.easeOut });
		dropdown.matchUlHeight();

		/* Smoothscroll */
		new SmoothScroll({ duration: 500, transition: Fx.Transitions.Expo.easeOut });

		/* Match height of div tags */
		function matchHeights() {
			YOOBase.matchHeight('div.headerbox div.deepest', 20);
			YOOBase.matchHeight('div.topbox div.deepest', 20);
			YOOBase.matchHeight('div.bottombox div.deepest', 20);
			YOOBase.matchHeight('div.maintopbox div.deepest', 20);
			YOOBase.matchHeight('div.mainbottombox div.deepest', 20);
			YOOBase.matchHeight('div.contenttopbox div.deepest', 20);
			YOOBase.matchHeight('div.contentbottombox div.deepest', 20);
		}

	}

};

/* Add functions on window load */
window.addEvent('domready', YOOTemplate.start);