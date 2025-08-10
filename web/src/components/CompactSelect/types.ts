import { Dayjs } from 'dayjs'

// 选项类型
export interface OptionType {
  label?: string | number
  value?: string | number | null
  placeholder?: string
  disabled?: boolean
}

export interface PresetType {
  label: string
  value: [Dayjs, Dayjs]
}

