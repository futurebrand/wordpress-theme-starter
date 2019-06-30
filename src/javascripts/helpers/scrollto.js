export default function ScrollTo(target, diff = 0) {
  let scrollTop = null;

  if (typeof target === 'number') {
    scrollTop = target;
  } else {
    const bodyRect = document.body.getBoundingClientRect();
    const targetRect = target.getBoundingClientRect();
    scrollTop = targetRect.top - bodyRect.top;
  }

  if (typeof window.scrollY === 'undefined') {
    window.scrollTo(0, scrollTop - diff);
  } else {
    window.scrollTo({
      top: scrollTop - diff,
      left: 0,
      behavior: 'smooth'
    });
  }
}
