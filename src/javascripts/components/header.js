class Header {
  initialize() {
    this.$el = document.querySelector('.header');

    if (!this.$el) return false;

    this.attachEvents();
  }

  attachEvents() {
    this.$el
      .querySelector('.header__toggle')
      .addEventListener('click', this._actionToggle.bind(this));
  }

  _actionToggle(event) {
    this.$el.classList.toggle('header--nav-active');
  }

  scroll(curTop) {
    if (curTop >= 100 && !this.sticky) {
      this.sticky = true;
      this.$el.classList.add('sticky');
    } else if (curTop < 100 && this.sticky) {
      this.sticky = false;
      this.$el.classList.remove('sticky');
    }
  }
}

export default Header;
