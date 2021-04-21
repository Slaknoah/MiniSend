import { $themeBreakpoints } from '@/themeConfig'


export const state = () => ({
  windowWidth: 0,
  shallShowOverlay: false,
});

export const getters = {
  currentBreakPoint: state => {
    const { windowWidth } = state
    if (windowWidth >= $themeBreakpoints.xl) return 'xl'
    if (windowWidth >= $themeBreakpoints.lg) return 'lg'
    if (windowWidth >= $themeBreakpoints.md) return 'md'
    if (windowWidth >= $themeBreakpoints.sm) return 'sm'
    return 'xs'
  },
}

export const mutations = {
  UPDATE_WINDOW_WIDTH(state, val) {
    state.windowWidth = val
  },
  TOGGLE_OVERLAY(state, val) {
    state.shallShowOverlay = val !== undefined ? val : !state.shallShowOverlay
  },
}