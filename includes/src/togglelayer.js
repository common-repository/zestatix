/**
 * Changes the inner HTML for the element after the event
 *
 * @see https://github.com/Mykola10987/ToggleLayer
 *
 * @param {Element} attr.el - element where the layers change
 * @param {HTML || [ HTML ]} attr.layer - layers to replace
 * @param {Boolean} true || attr.loop - repeat after completion
 * @param {Boolean} 'click' || attr.e:
 *  'hover' - change layers only when hovering on attr.el
 *  'click' - change layer after click
 *  'loaded' - change layers immediately after loading
 * @param {number} 500 || attr.fade - fade effect when changing the layer
 * @param {number} 1000 || attr.delay - delay before change
 */

class ToggleLayer {
  constructor(attr) {
    if (typeof attr != 'object') return false

    if (attr.el instanceof NodeList) this.attr_el = Array.from(attr.el)
    else if (attr.el instanceof Element) this.attr_el = [attr.el]
    else {
      console.error('ToggleLayer: Need to use the element.')

      return false
    }

    this.el = []

    this.create(attr)
  }

  create(attr) {
    for (let el of this.attr_el) {
      let tl_el = new ToggleLayerElement(el, attr)

      tl_el.init()

      this.el.push(tl_el)
    }
  }

  get(el) {
    if (!el) return this.el

    if (!(el instanceof NodeList) || !(el instanceof Element)) return

    el = el instanceof Element ? [el] : Array.from(el)

    return this.el.filter(element => el.includes(element))
  }

  toggle(toggler, el) {
    let toggle = this.get(el)

    toggle.forEach(element => {
      element.toggle(toggler)
    })
  }

  add_layer(layer, el) {
    let result = []

    if (!ToggleLayerlayer.valid(layer)) return result

    el = this.get(el)

    el.forEach(element => {
      result.push(element.layers.create(layer))
    })

    return result
  }

  stop(el) {
    let stop = this.get(el)

    stop.forEach(element => {
      element.stop = true
    })
  }
}

class ToggleLayerlayer {
  constructor() {
    this.layers = []

    this.id = -1
  }

  get(id) {
    return this.layers?.[id]
  }

  static valid(layers) {
    return (
      typeof layers == 'string' ||
      (typeof layers[Symbol.iterator] == 'function' && !layers.find(layer => typeof layer != 'string'))
    )
  }

  create(layers) {
    let result = []

    if (typeof layers == 'string') layers = [layers]

    for (let layer of layers) {
      let root = document.createElement('tl-layer')

      root.innerHTML = layer

      root.layer_id = ++this.id

      result.push(root)
    }

    this.layers = [...this.layers, ...result]

    return result
  }
}

class ToggleLayerElement {
  constructor(el, attr) {
    this.el = el

    this.attr_layer = typeof attr.layer == 'string' ? [attr.layer] : attr.layer ?? []

    this.e = typeof attr.e == 'string' && ['hover', 'click', 'loaded'].includes(attr.e) ? attr.e : 'click'

    this.loop = typeof attr.loop == 'boolean' ? attr.loop : true

    this.fade = +attr?.fade >= 0 ? attr.fade : 500

    this.delay = +attr?.delay >= 0 ? attr.delay : 1000

    this.stop = false
  }

  init() {
    this.layers = new ToggleLayerlayer()

    let layers = [this.el.innerHTML, ...this.attr_layer]

    if (ToggleLayerlayer.valid(layers)) layers = this.layers.create(layers)
    else return console.error('ToggleLayer: error in data for layers')

    if (this.fade)
      layers.forEach(layer => {
        layer.style.opacity = layer.layer_id != 0 ? 0 : 1
      })

    this.el.innerHTML = ''

    this.layer = this.insert(this.layers.get(0))

    this.add_event()
  }

  insert(layer = this.layer) {
    let _layer = layer.cloneNode(true)

    _layer.layer_id = layer.layer_id

    this.el.append(_layer)

    return this.el.querySelector('tl-layer')
  }

  async toggle(toggler, once = false, layer) {
    const toggle = async layer => {
      await this.hide()

      this.layer = this.insert(layer)

      await this.show()
    }

    const loop = async layer => {
      while (this.executing && !this.stop) {
        await new Promise(res => {
          let delay = this.delay

          this.timeout = setTimeout(res, delay)
        })

        if (!layer) {
          layer = this.layers.get(this.layer.layer_id + 1)

          if (!layer && this.loop) layer = this.layers.get(0)
        }

        if (this.executing && layer) await toggle(layer)

        if (once) {
          this.executing = false

          return
        }

        layer = ''
      }

      this.executing = false
    }

    if (typeof toggler == 'boolean' && !toggler) {
      this.executing = false

      clearTimeout(this.timeout)

      if (layer) await toggle(layer)

      return
    }

    this.executing = true

    await loop(layer ?? '')
  }

  add_event() {
    if (this.stop) return

    switch (this.e) {
      case 'hover':
        this.el.addEventListener(
          'mouseover',
          async () => {
            await this.toggle(true)

            if (this.layer.layer_id > 0) {
              await this.toggle(false, true, this.layers.get(0))
            }

            this.add_event()
          },
          { once: true }
        )

        this.el.addEventListener(
          'mouseleave',
          async () => {
            this.executing = false
          },
          { once: true }
        )

        break

      case 'click':
        this.el.addEventListener(
          'click',
          async () => {
            await this.toggle(true, true)

            this.add_event()
          },
          { once: true }
        )

        break

      case 'loaded':
        document.addEventListener('DOMContentLoaded', async () => {
          await this.toggle(true)
        })

        break
    }
  }

  async show(layer = this.layer) {
    if (this.fade) await this.fade_out(layer)
  }

  async hide(layer = this.layer) {
    if (this.fade) await this.fade_in(layer)

    this.el.innerHTML = ''
  }

  fade_in(node, interval = 300) {
    return new Promise(res => {
      node.style.opacity = node.style.opacity || 1

      let interval_id = setInterval(() => {
        if (+node.style.opacity > 0) node.style.opacity = +node.style.opacity - 0.1
        else {
          clearInterval(interval_id)

          res()
        }
      }, interval / 10)
    })
  }

  fade_out(node, interval = 300) {
    return new Promise(res => {
      node.style.opacity = node.style.opacity || 0

      let interval_id = setInterval(() => {
        if (+node.style.opacity < 1) node.style.opacity = +node.style.opacity + 0.1
        else {
          clearInterval(interval_id)

          res()
        }
      }, interval / 10)
    })
  }
}
