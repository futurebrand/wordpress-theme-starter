class Home {
  initialize() {
    this.$el = document.querySelector('.home');

    if (!this.$el) return false;

    this.attachEvents();
  }

  attachEvents() {}
}

export default Home;
