// components
import Header from './components/header';

// views
import Home from './views/home';

class App {
  initialize() {
    this.oldCurTop = 0;
    this.components = [new Header()];

    this.setComponents();
    this.setViews();
    this.attachEvents();
  }

  attachEvents() {
    if (window.addEventListener) {
      window.addEventListener('resize', this.handleResize.bind(this), true);
    } else {
      window.attachEvent('onresize', this.handleResize.bind(this));
    }

    if (window.addEventListener) {
      window.addEventListener('scroll', this.handleScroll.bind(this), true);
    } else {
      window.attachEvent('onscroll', this.handleScroll.bind(this));
    }
  }

  triggerResize() {
    if (typeof Event === 'function') {
      window.dispatchEvent(new Event('resize'));
    } else {
      const resizeEvent = window.document.createEvent('UIEvents');
      resizeEvent.initUIEvent('resize', true, false, window, 0);
      window.dispatchEvent(resizeEvent);
    }
  }

  handleResize() {
    const width = window.innerWidth;
    const height = window.innerHeight;

    this.components.forEach(component => {
      if (typeof component.resize === 'function') {
        component.resize(width, height);
      }
    });

    if (this.view && typeof this.view.resize === 'function') {
      this.view.resize(width, height);
    }
  }

  handleScroll() {
    const curTop =
      typeof window.scrollY === 'undefined'
        ? window.pageYOffset
        : window.scrollY;

    if (this.components.length) {
      this.components.forEach(component => {
        if (typeof component.scroll === 'function') {
          component.scroll(curTop, this.oldCurTop);
        }
      });
    }

    if (this.view && typeof this.view.scroll === 'function') {
      this.view.scroll(curTop, this.oldCurTop);
    }

    this.oldCurTop = curTop;
  }

  setComponents() {
    this.components.forEach(component => {
      component.initialize();
    });
  }

  setViews() {
    if (document.querySelector('.home')) {
      this.view = new Home();
    }

    if (this.view) {
      this.view.initialize();
    }
  }
}

window.onload = () => {
  let app = null;

  window.app = app = new App();
  app.initialize();
};
