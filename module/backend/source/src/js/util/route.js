const query = new Proxy(new URLSearchParams(window.location.search), {
  get: (searchParams, prop) => searchParams.get(prop),
});

const route = {
  uri: window.location.pathname,
  url: window.location.href,
  base: `${window.location.protocol}//${window.location.host}`,
  query,
  // TODO resolve path
};

export default route;
