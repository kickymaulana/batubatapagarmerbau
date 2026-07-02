const Ziggy = {"url":"http:\/\/localhost\/batubatapagarmerbau\/public","port":null,"defaults":{},"routes":{"home":{"uri":"\/","methods":["GET","HEAD"]},"testing.route":{"uri":"tes-parameter\/{id}","methods":["GET","HEAD"],"parameters":["id"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]},"storage.local.upload":{"uri":"storage\/{path}","methods":["PUT"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
