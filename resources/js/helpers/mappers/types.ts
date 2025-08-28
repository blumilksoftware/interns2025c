export interface GenderInfo {
  symbol: string;
  color: string;
  label: string;
}

export interface GenderMapper {
  male: GenderInfo;
  female: GenderInfo;
  unknown: GenderInfo;
}

export interface PetTagInfo {
  name: string;
  emoji: string;
  color: string;
}

export interface PetTagsConfig {
  friendly: PetTagInfo;
  gentle: PetTagInfo;
  energetic: PetTagInfo;
  playful: PetTagInfo;
  calm: PetTagInfo;
  loyal: PetTagInfo;
  smart: PetTagInfo;
  protective: PetTagInfo;
  social: PetTagInfo;
  active: PetTagInfo;
}

export interface CharacteristicInfo {
  icon: string;
  label: string;
}

export interface CharacteristicsMapper {
  age: CharacteristicInfo;
  breed: CharacteristicInfo;
  gender: CharacteristicInfo;
}

export interface CharacteristicDisplay {
  key: string;
  label: string;
  value: string;
  icon: string;
}

export interface MedicalInfoItem {
  icon: string;
  bgColor: string;
  label: string;
  description: string;
}

export interface MedicalInfoMapper {
  vaccinated: MedicalInfoItem;
  dewormed: MedicalInfoItem;
  microchipped: MedicalInfoItem;
  sterilized: MedicalInfoItem;
}

export interface MedicalInfoDisplay {
  type: string;
  icon: string;
  bgColor: string;
  label: string;
  description: string;
}

export interface HealthStatusInfo {
  icon: string;
  bgColor: string;
  label: string;
  description: string;
}

export interface HealthStatusMapper {
  healthy: HealthStatusInfo;
  sick: HealthStatusInfo;
  recovering: HealthStatusInfo;
  critical: HealthStatusInfo;
  unknown: HealthStatusInfo;
}

export interface StatusInfo {
  bgColor: string;
  dotColor: string;
}

export interface StatusMapper {
  available: StatusInfo;
  unavailable: StatusInfo;
}

export interface Pet {
  id: number;
  name: string;
  breed: string;
  age?: string;
  gender?: string;
  sex?: string;
  vaccinated?: boolean;
  dewormed?: boolean;
  microchipped?: boolean;
  sterilized?: boolean;
  tags?: string[];
  imageUrl?: string;
  status?: string;
  description?: string;
}
