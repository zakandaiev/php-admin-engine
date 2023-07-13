const BASE_URL = window.location.protocol + '//' + window.location.host;
const PATH_URL = window.location.pathname;
const FULL_URL = window.location.href;
const GET_PARAM = (key) => {
	return new URL(FULL_URL).searchParams.get(key);
};

console.log('%cMade by Zakandaiev', 'background:#da4648;color:#fff;padding:10px;font-weight:bold;');


// UTILS
function fadeOut(element, soft = false, callback = null) {
	if(!element) {
		return false;
	}

	element.style.opacity = 1;

	(function fade() {
		if((element.style.opacity -= 0.1) < 0) {
			if(soft) {
				element.style.display = "none";
			} else {
				element.remove();
			}

			if(callback instanceof Function) {
				callback();
			} else if(window[callback] instanceof Function) {
				window[callback]();
			}
		} else {
			requestAnimationFrame(fade);
		}
	})();

}

const sleep = (ms) => {
  return new Promise(resolve => setTimeout(resolve, ms))
}

function smoothScroll(element) {
	if(element) {
		element.scrollIntoView({
			behavior: 'smooth'
		});
	}
}

function smoothScrollToTop() {
	window.scrollTo({
		top: 0,
		behavior: 'smooth'
	});
}

document.addEventListener('click', event => {
	if(event.target.tagName !== 'A') {
		return false;
	}
	const anchor = event.target;
	const anchor_href = anchor.getAttribute('href');

	if(anchor_href === '#') {
		event.preventDefault();
		smoothScrollToTop();
	}
	else if(anchor_href.charAt(0) === '#' || (anchor_href.charAt(0) === '/' && anchor_href.charAt(1) === '#')) {
		if(!anchor.hash) {
			return false;
		}

		const target = document.querySelector(anchor.hash);
		if(target) {
			event.preventDefault();
			smoothScroll(target);
		}
	}
});

window.onload = () => {
	document.querySelectorAll('img').forEach(image => {
		if(image.complete && typeof image.naturalWidth != 'undefined' && image.naturalWidth <= 0) {
			image.src = BASE_URL + '/img/no-image.jpg';
		}
	});
};


document.addEventListener('DOMContentLoaded', () => {
	// PARTIALS
	document.addEventListener('click', event => {
	const accordion = event.target.closest('.accordion');

	if (!accordion) {
		return false;
	}

	const header = accordion.querySelector('.accordion__header');
	const body = accordion.querySelector('.accordion__body');

	if (!header || !body) {
		return false;
	}

	event.preventDefault();

	const is_collapse = (accordion.parentElement && (accordion.parentElement.hasAttribute('data-collapse') || accordion.parentElement.getAttribute('data-collapse') == true));
	if (is_collapse) {
		accordion.parentElement.querySelectorAll('.accordion').forEach(a => {
			if(a === accordion) {
				return false;
			}

			a.classList.remove('active');

			const b = a.querySelector('.accordion__body');
			if(b) {
				b.style.height = '0px';
			}
		});
	}

	const body_height = body.scrollHeight;
	if (accordion.classList.contains('active')) {
		body.style.height = '0px';
		accordion.classList.remove('active');
	}
	else {
		body.style.height = `${body_height}px`;
		accordion.classList.add('active');
	}
});

	document.querySelectorAll('a').forEach(anchor => {
	if(anchor.hasAttribute('href') && anchor.href.startsWith('tel:')) {
		anchor.href = 'tel:' + anchor.href.replaceAll(/[^\d+]/g, '');
	}
});

	document.querySelectorAll('a').forEach(anchor => {
	if(anchor.hasAttribute('target') && anchor.getAttribute('target') === '_blank') {
		anchor.setAttribute('rel', 'noopener noreferrer nofollow');
	}
});

	document.addEventListener('contextmenu', event => {
	if(event.target.nodeName === 'IMG') {
		event.preventDefault();
	}
});

	document.querySelectorAll('table').forEach(table => {
	if(!table.parentElement.classList.contains('table-responsive')) {
		table.outerHTML = '<div class="table-responsive">' + table.outerHTML + '</div>';
	}
});

	// TOOLTIP
document.addEventListener('mouseover', async (event) => {
	const tooltip = event.target.closest('[data-tooltip]');

	if (!tooltip) {
		return false;
	}

	const placement = tooltip.getAttribute('data-tooltip') || 'top';
	const content = tooltip.getAttribute('data-title');

	if(tooltip.parentElement.classList.contains('tooltip-wrapper')) {
		return false;
	}

	const wrapper = document.createElement('div');
	wrapper.classList.add('tooltip-wrapper');
	wrapper.style.display = getComputedStyle(tooltip).getPropertyValue('display');

	const tip = document.createElement('span');
	tip.classList.add('tooltip');
	tip.classList.add(`tooltip_${placement}`);
	tip.textContent = content;

	tooltip.parentNode.insertBefore(wrapper, tooltip);
  wrapper.appendChild(tooltip);
  wrapper.appendChild(tip);
});

});
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiIiwic291cmNlcyI6WyJtYWluLmpzIl0sInNvdXJjZXNDb250ZW50IjpbImNvbnN0IEJBU0VfVVJMID0gd2luZG93LmxvY2F0aW9uLnByb3RvY29sICsgJy8vJyArIHdpbmRvdy5sb2NhdGlvbi5ob3N0O1xyXG5jb25zdCBQQVRIX1VSTCA9IHdpbmRvdy5sb2NhdGlvbi5wYXRobmFtZTtcclxuY29uc3QgRlVMTF9VUkwgPSB3aW5kb3cubG9jYXRpb24uaHJlZjtcclxuY29uc3QgR0VUX1BBUkFNID0gKGtleSkgPT4ge1xyXG5cdHJldHVybiBuZXcgVVJMKEZVTExfVVJMKS5zZWFyY2hQYXJhbXMuZ2V0KGtleSk7XHJcbn07XHJcblxyXG5AQGluY2x1ZGUoJ3BhcnRpYWwvd2F0ZXJtYXJrLmpzJylcclxuXHJcbi8vIFVUSUxTXHJcbkBAaW5jbHVkZSgndXRpbC9mYWRlLW91dC5qcycpXHJcbkBAaW5jbHVkZSgndXRpbC9zbGVlcC5qcycpXHJcbkBAaW5jbHVkZSgndXRpbC9zbW9vdGgtc2Nyb2xsLmpzJylcclxuQEBpbmNsdWRlKCd1dGlsL3JlcGxhY2UtYnJva2VuLWltYWdlLmpzJylcclxuXHJcbmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCAoKSA9PiB7XHJcblx0Ly8gUEFSVElBTFNcclxuXHRAQGluY2x1ZGUoJ3BhcnRpYWwvYWNjcm9kaW9uLmpzJylcclxuXHRAQGluY2x1ZGUoJ3BhcnRpYWwvZm9ybWF0LXRlbC1saW5rLmpzJylcclxuXHRAQGluY2x1ZGUoJ3BhcnRpYWwvZXh0ZXJuYWwtbGluay1ub3JlZmVyLmpzJylcclxuXHRAQGluY2x1ZGUoJ3BhcnRpYWwvcHJvdGVjdC1pbWFnZS5qcycpXHJcblx0QEBpbmNsdWRlKCdwYXJ0aWFsL3Jlc3BvbnNpdmUtdGFibGUuanMnKVxyXG5cdEBAaW5jbHVkZSgncGFydGlhbC90b29sdGlwLmpzJylcclxufSk7XHJcbiJdLCJmaWxlIjoibWFpbi5qcyJ9
